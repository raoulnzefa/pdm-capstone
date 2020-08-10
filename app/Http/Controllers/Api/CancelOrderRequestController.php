<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Traits\UserLogs;
use App\CancelledOrder;
use App\CancelledProduct;
use App\Inventory;
use App\PaypalPayment;
use App\Notifications\DeclinedCancellationNotification;
use App\Notifications\ApprovedNotPaidCancellationNotif;
use App\Notifications\ApprovedPaidCancellationNotif;
use App\Notifications\RefundCancellationNotif;
use App\CancelOrderRequest;
use App\CancelProductRequest;
use App\Order;
use App\OrderProduct;
use App\Customer;
use App\Invoice;
use App\InvoiceProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PayPal\Api\Amount;
use PayPal\Api\RefundRequest;
use PayPal\Api\Sale;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Exception\PayPalConnectionException;
use Exception;
use Config;

class CancelOrderRequestController extends Controller
{
    use  UserLogs;

	private $_api_context;
    
    public function __construct()
    {
        $paypal_conf = Config::get('paypal');

        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret']
            
        ));

        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function storeCancelRequest(Request $request)
    {
    	$request->validate([
    		'reason' => 'required',
    	]);

    	// set time zone
    	date_default_timezone_set("Asia/Manila");

    	// save cancel order request
    	$cancelOrderReq = new CancelOrderRequest;
    	$cancelOrderReq->customer_id = $request->customer_id;
    	$cancelOrderReq->order_number = $request->order_number;
    	$cancelOrderReq->reason_id = $request->reason;

    	// check order quantity if greater than 1
    	if ($request->cancel_request_type == 'Partial')
    	{
            // get total quantity order
            $order_qty = OrderProduct::where('order_number', $request->order_number)->sum('quantity');
            // total cancel qty
            $cancel_qty = 0;

            foreach($request->cancel_products as $item)
            {
                $cancel_qty += $item['cancel_qty'];
            }

            if ($order_qty == $cancel_qty)
            {
                $cancelOrderReq->action = 'Full Cancellation';
            }
            else
            {
                $cancelOrderReq->action = 'Partial Cancellation';
            }
    		
    	}
    	else
    	{
    		$cancelOrderReq->action = 'Full Cancellation';
    	}

    	if ($request->filled('comment'))
    	{
    		$cancelOrderReq->comment = $request->comment;
    	}

    	$cancelOrderReq->status = 'Pending';
    	$cancelOrderReq->date_request = date('Y-m-d H:i:s');
    	$cancelOrderReq->save();


    	// save cancel product request
         if ($cancelOrderReq->action == 'Partial Cancellation')
        {
            foreach ($request->cancel_products as $item)
            {
                if ($item['cancel_qty'] > 0)
                {
                    $cancelProd = new CancelProductRequest;
                    $cancelProd->cancel_order_request_id = $cancelOrderReq->id;
                    $cancelProd->order_product_id = $item['id'];
                    $cancelProd->quantity = $item['cancel_qty'];
                    $cancelProd->ordered_qty = $item['quantity'];
                    $cancelProd->amount = $cancelProd->quantity * str_replace(',', '',$item['price']);
                    $cancelProd->save();
                }
        
            }
        }
        else
        {
            // if full cancellation
            foreach ($request->cancel_products as $item)
            {                
                $cancelProd = new CancelProductRequest;
                $cancelProd->cancel_order_request_id = $cancelOrderReq->id;
                $cancelProd->order_product_id = $item['id'];
                $cancelProd->quantity = $item['quantity'];
                $cancelProd->ordered_qty = $item['quantity'];
                $cancelProd->amount = $cancelProd->quantity * str_replace(',', '',$item['price']);

                $cancelProd->save();
            }
        
        }

    	

    	return response()->json(['success'=> true]);
    }

    public function cancelRequestList()
    {
    	$cancel_request_list = CancelOrderRequest::orderBy('created_at', 'desc')->with('customer')->paginate(10);

    	return response()->json($cancel_request_list);
    }

    public function customerCancelRequestList($customer)
    {
        $customer_cancel_requests = CancelOrderRequest::where('customer_id', $customer)
                                ->with(
                                    'reason',
                                    'cancelProductRequests.orderProduct.inventory.product'
                                )
                                ->orderBy('id','desc')->get();

        return response()->json($customer_cancel_requests);
    }

    public function cancelRequestDetails($cancel)
    {
    	// get cancel request details
    	$cancel_request_details = CancelOrderRequest::where('id', $cancel)
                                ->with(
                                    'order.cancelledOrder',
                                    'reason',
                                    'cancelProductRequests.orderProduct.inventory.product'
                                )->first();
    	
        // get order details
        $order = Order::where('number', $cancel_request_details->order_number)
                        ->with('customer')->first();
         // cancel_qty
        $cancel_qty = CancelProductRequest::where('cancel_order_request_id', $cancel)->sum('quantity');

        if ($cancel_request_details->action == 'Full Cancellation')
    	{

            $response = $this->computeCancellationAmount($cancel_request_details, $order);

    	}
    	else
    	{
    	   	$response = $this->computeCancellationAmount($cancel_request_details, $order);
    	}
        
    	return response()->json($response);
    }

    public function updateCancelStatus(Request $request, CancelOrderRequest $cancel)
    {
         // set time zone
        date_default_timezone_set("Asia/Manila");
        // get customer detail
        $customer = Customer::where('id', $cancel->customer_id)->first();

        $amount_details = array(
                'subtotal' => number_format($request->subtotal, 2),
                'discount' => number_format($request->discount, 2),
                'shipping_fee' => number_format($request->shipping_fee, 2),
                'total' => number_format($request->total, 2)
            );
        
    	// check if cancel request if approve
    	if ($request->cancellation_action == 'Approve')
    	{  
            

            // if order has not been paid yet
    		if ($cancel->order->payment_status != 'Paid')
            {

                // if full cancellation cancel whole order
                if ($cancel->action == 'Full Cancellation')
                {
                    // restock product
                    // update inventory
                    $this->restockCancelledProduct($cancel);

                    // updat order status
                    $order = Order::where('number', $cancel->order_number)->first();
                    $order->status = 'Cancelled';
                    $order->payment_status = 'Cancelled';
                    $order->date_cancelled = date('Y-m-d');
                    $order->update();

                    $msg = 'Cancellation has been approved and order has been cancelled.';
                    // update cancellation status
                    $cancel->status = 'Order Cancelled';
                    $cancel->update();

                    // send full cancellation notification
                    try
                    {
                        
                        $customer->notify(new ApprovedNotPaidCancellationNotif($cancel, $amount_details));

                        // $cancel_params = [
                        //     'id' => $request->admin_id,
                        //     'action' => 'Approved cancellation request, order has been cancelled. Order Number: '.$order->number
                        // ];

                        //$this->createUserLog($cancel_params);

                        $response = ['success' => true, 'suc_msg'=> $msg];

                    }
                    catch(Exception $ex)
                    {
                        $response = ['success' => false, 'err_msg'=> $ex->getMessage()];
                    }
                }
                else
                {
                    // restock product
                    // update inventory
                    $this->restockCancelledProduct($cancel);
                    // update order prodyct
                    $this->updateOrderProduct($cancel);

                    $order = Order::where('number', $cancel->order_number)->first();

                    $this->deleteZeroQuantity($order->orderProducts);

                    //update order details
                    $this->updateOrderDetails($cancel);

                    $msg = 'Cancellation has been approved.';

                    // update cancellation status
                    $cancel->status = 'Product Cancelled';
                    $cancel->update();

                    try
                    {
                       
                        $customer->notify(new ApprovedNotPaidCancellationNotif($cancel, $amount_details));

                        // $cancel_params = [
                        //     'id' => $request->admin_id,
                        //     'action' => 'Approved cancellation request. Order Number: '.$order->number
                        // ];

                        // $this->createUserLog($cancel_params);

                        $response = ['success' => true, 'suc_msg'=> $msg];

                    }
                    catch(Exception $ex)
                    {
                        $response = ['success' => false, 'err_msg'=> $ex->getMessage()];
                    }   
                }


            }
            else // if order has been paid
            {

                $msg = 'Cancellation has been approved.';

                // update cancellation status
                $cancel->status = 'Awaiting Refund';
                $cancel->update();

                // send approved cancellation notification
                try
                {

                    $customer->notify(new ApprovedPaidCancellationNotif($cancel, $amount_details));

                    // $cancel_params = [
                    //     'id' => $request->admin_id,
                    //     'action' => 'Approved cancellation request. Order Number: '.$cancel->order_number
                    // ];

                    // $this->createUserLog($cancel_params);

                    $response = ['success' => true, 'suc_msg' => $msg];
                }
                catch(Exception $ex)
                {
                    $response = ['success' => false, 'err_msg' => $ex->getMessage() ];
                }
            }

    	}

        if ($request->cancellation_action == 'Decline')
        {
            //return response()->json($request->decline_message);
            // update cancellation status
            $message = $request->decline_message;
            $cancel->status = 'Declined';
            $cancel->update();
            // if decline
            $msg = 'Cancellation has been declined.';

            $order = Order::where('number', $cancel->order_number)->first();
            // send declined cancellation notification
            try
            {

                $customer->notify(new DeclinedCancellationNotification($cancel, $amount_details, $message));

                // $cancel_params = [
                //     'id' => $request->admin_id,
                //     'action' => 'Declined cancellation request, order has been cancelled. Order Number: '.$order->number
                // ];

                // $this->createUserLog($cancel_params);

                $response = ['success' => true, 'suc_msg' => $msg];
            }
            catch(Exception $ex)
            {
                $response = ['success' => false, 'err_msg' => $ex->getMessage() ];
            }
        }
        
       

    	return response()->json($response);
    }

    public function cancelRequestRefund(Request $request, CancelOrderRequest $cancel)
    {
    	// set timezone
 		date_default_timezone_set("Asia/Manila");

 		// refund amount
 		$amount_refund = str_replace(',', '', $request->total_refund); 
        // customer
        $customer = Customer::where('id', $cancel->customer_id)->first();

    	// check payment method
    	if ($cancel->order->payment_method == 'PayPal')
    	{
    		$saleId = PaypalPayment::where('order_number', $cancel->order_number)
 									->first()->transaction_id;

 			$amount = new Amount();
 			$amount->setCurrency('PHP')
 				->setTotal($amount_refund);

 			$refundRequest = new RefundRequest();
 			$refundRequest->setAmount($amount);

 			$sale = new Sale();
 			$sale->setId($saleId);

 			try
 			{
 				$refundedSale = $sale->refundSale($refundRequest, $this->_api_context);

 				if ($refundedSale->getState() == 'completed')
 				{
 					// update cancel request status to refunded
 					$cancel->status = 'Refunded';
 					$cancel->update();


 					//$response = ['success'=>true, 'state'=> $refundedSale->getState()];
 				}
 			}
 			catch(PayPalConnectionException $e)
 			{
 				return response()->json(['success' => false, 'err_msg'=> $e->getCode()]);
 			}

    	}
        
        $amount_details = array(
                'subtotal' => number_format($request->subtotal, 2),
                'discount' => number_format($request->discount, 2),
                'shipping_fee' => number_format($request->shipping_fee, 2),
                'total' => number_format($request->total_refund, 2)
            );

        if ($cancel->action == 'Full Cancellation')
        {
            // updat order status
            $order = Order::where('number', $cancel->order_number)->first();
            $order->status = 'Cancelled';
            $order->payment_status = 'Refunded';
            $order->date_cancelled = date('Y-m-d');
            $order->update();

            // restock product
            // update inventory
            $this->restockCancelledProduct($cancel);

            try
            {
                // send cancelled products if partial cancellation
                // if full cancellation send order details
                $customer->notify(new RefundCancellationNotif($cancel, $amount_details));
                
                // $refund_params = [
                //     'id' => $request->admin_id,
                //     'action' => 'Create a refund for cancellation. Order Number: '.$cancel->order_number.'.'
                // ];

                // $this->createUserLog($refund_params);

                $response = ['success' => true];
            }
            catch(Exception $ex)
            {
                $response = ['success' => false, 'err_msg' => $ex->getMessage()];
            }

        }
        else
        {
            // updat order status
            $order = Order::where('number', $cancel->order_number)->first();
            $order->date_updated = date('Y-m-d H:i:s');
            $order->update();

            //
            $this->updateOrderProduct($cancel);

            //
            $this->updateOrderDetails($cancel);

            $this->deleteZeroQuantity($order->orderProducts);

            $order_data = Order::where('number', $cancel->order_number)->first();

            $array_params = ['order'=>$order_data];

            $this->generateInvoice($array_params);

            // Void invoice status
            $void_invoice = Invoice::where('number', $request->invoice_number)->first();
            $void_invoice->status = 'Void';
            $void_invoice->update();

            $cancel_params = array(
                'order'=>$cancel->order,
                'subtotal'=> $request->subtotal,
                'discount'=>$request->discount,
                'shipping_fee' => $request->shipping_fee,
                'total'=> $amount_refund,
                'cancel'=>$cancel
            );
            // save partially cancelled order
            $this->saveCancelledPaidOrder($cancel_params);

            // restock product
            // update inventory
            $this->restockCancelledProduct($cancel);

            try
            {
                // send cancelled products if partial cancellation
                // if full cancellation send order details
                $customer->notify(new RefundCancellationNotif($cancel,$amount_details));
                
                // $refund_params = [
                //     'id' => $request->admin_id,
                //     'action' => 'Create a refund for cancellation. Order Number: '.$cancel->order_number.'.'
                // ];

                // $this->createUserLog($refund_params);

                $response = ['success' => true];
            }
            catch(Exception $ex)
            {
                $response = ['success' => false, 'err_msg' => $ex->getMessage()];
            }
        }

        $array_params = array(
            'order' => $cancel->order,
            'invoice_number' => $request->invoice_number,
            'type'=> 'Cancellation',
            'subtotal'=> $request->subtotal,
            'discount' => $request->discount,
            'shipping_fee' => $request->shipping_fee,
            'total'=> $amount_refund,
            'products'=> $cancel->cancelProductRequests
        );


        $this->createSalesReturn($array_params);

        $cancel->status = 'Refunded';
        $cancel->update();

    	// send emails
    	return response()->json($response);
    }

    private function restockCancelledProduct($cancel)
    {
        $restock_qty = 0;
        // restock product that is cancelled
        foreach ($cancel->cancelProductRequests as $item)
        {
            $inventory = Inventory::where('sku', $item['orderProduct']['inventory_sku'])->first();
            $inventory->quantity += $item['quantity'];
            $inventory->availability = 'In stock';
            $inventory->update();

            //$restock_qty +=  $item['quantity'];
        }   

        //return $restock_qty;
    }

    private function updateOrderProduct($cancel)
    {
        foreach ($cancel->cancelProductRequests as $item)
        {
            // update order product
            $orderProduct = OrderProduct::where('id', $item['order_product_id'])->first();
            $orderProduct->quantity -= $item['quantity'];
            $orderProduct->total = $orderProduct->quantity * str_replace(',','',$orderProduct->price);
            $orderProduct->shipping_fee = $orderProduct->quantity * str_replace(',','',$orderProduct->shipping_rate);
            $orderProduct->update();
        }   
    }

    private function updateOrderDetails($cancel)
    {
        // order quantity
        $order_qty = OrderProduct::where('order_number', $cancel->order_number)->sum('quantity');
        // shipping fee
        $shipping_fee = OrderProduct::where('order_number', $cancel->order_number)->sum('shipping_fee');
        // subtotal
        $subtotal = OrderProduct::where('order_number', $cancel->order_number)->sum('total');

        if ($order_qty >= 12)
        {
            $discount_amount = $subtotal * (10/100);
            $new_subtotal = $subtotal - $discount_amount;

        }
        else
        {
            $new_subtotal = $subtotal;
            $discount_amount = NULL;
        }

        
         // order
        $order = Order::where('number', $cancel->order_number)->first();
        $order->quantity = $order_qty;
        $order->subtotal = $new_subtotal;
        $order->discount = $discount_amount;

        if ($order->shipping_method == 'Shipping')
        {
            $order_shipping_fee = $shipping_fee;
        }
        else
        {
            $order_shipping_fee = NULL;
        }

        $order->shipping_cost = $order_shipping_fee;
        $order->total = $new_subtotal + $order_shipping_fee;
        $order->update();
       
    }

    private function deleteZeroQuantity($order_products)
    {
        foreach ($order_products as $item)
        {
            // delete 0 quantity
            OrderProduct::where('id', $item['order_product_id'])
                        ->where(function($query) {
                            $query->where('quantity', '=', 0);
                        })->delete();   
        }   
    }

    private function saveCancelledPaidOrder($array_params)
    {
        // set timezone
        date_default_timezone_set("Asia/Manila");

        // saves partially cancelled paid orders
        $cancelledOrder = new CancelledOrder;
        $cancelledOrder->order_number = $array_params['order']['number'];
        $cancelledOrder->date_cancelled = date('Y-m-d H:i:s');
        $cancelledOrder->subtotal = $array_params['subtotal'];
        $cancelledOrder->discount = $array_params['discount'];
        $cancelledOrder->shipping_fee = $array_params['shipping_fee'];
        $cancelledOrder->total = $array_params['total'];
        $cancelledOrder->save();

        // save cancelled products
        foreach ($array_params['cancel']['cancelProductRequests'] as $item)
        {
            if ($item['quantity'] > 0)
            {
                $cancelledProd = new CancelledProduct;
                $cancelledProd->cancelled_order_id = $cancelledOrder->id;
                $cancelledProd->order_product_id = $item['order_product_id'];
                $cancelledProd->quantity = $item['quantity'];
                $cancelledProd->amount = str_replace(',', '', $item['amount']);
                $cancelledProd->save();
            }
        }
       
    }

    public function withdrawCancellation($order)
    {
        $cancellation = CancelOrderRequest::where('order_number', $order)->first();

        $cancellation->status = 'Withdrawn';
        $cancellation->update();

        $response = ['success' => true];
        return response()->json($response);
    }

    private function computeCancellationAmount($cancel_request_details, $order)
    {
        // set time zone
        date_default_timezone_set("Asia/Manila");

        $date_requested = strtotime($cancel_request_details->date_request);

        $current_date = strtotime(date('Y-m-d H:i:s'));
        // getting the value of old date + 24 hours (86400)
        $cancelDateRequest =  (int)$current_date - (int)$date_requested;

        if ((int)$cancelDateRequest >= 86400)
        {
            $withdrawal_expired = true; 
        }
        else
        {
            $withdrawal_expired = false;
        }

        $discount = str_replace(',', '', $order->discount);
        
        $subtotal = CancelProductRequest::where('cancel_order_request_id', $cancel_request_details->id)->sum('amount');

        $cancel_qty = CancelProductRequest::where('cancel_order_request_id', $cancel_request_details->id)->sum('quantity');

        $remaining_qty = (int)$order->quantity - (int)$cancel_qty;

        $discount_per_product = $discount / (int)$order->quantity;


        if ((int)$remaining_qty >= 12)
        {
            $discount_for_cancellation = $cancel_qty * $discount_per_product; 
        }
        else
        {
            $discount_for_cancellation = $discount;
        }

        $shipping_fee = 0;
        // if order is shippping it will be added to the total amount to refund
        // get the shipping rate per product
        if ($order->shipping_method == 'Shipping')
        {
            foreach ($cancel_request_details->cancelProductRequests as $item)
            {
                $shipping_fee += $item['quantity'] * str_replace(',', '', $item['orderProduct']['shipping_rate']);
            }
        }

        $new_subtotal = $subtotal - $discount_for_cancellation;

        $amount = $new_subtotal + $shipping_fee;

        $invoice = Invoice::where('order_number','=', $order->number)->first();

        return $response = [
                'cancel_request_details'=> $cancel_request_details,
                'subtotal' => $subtotal,
                'discount'=> $discount_for_cancellation,
                'shipping_fee'=>$shipping_fee,
                'total'=>$amount,
                'withdrawal_expired' => $withdrawal_expired,
                'cancel_date' => $cancelDateRequest,
                'curr_date' => $current_date,
                'date_requested' => $date_requested,
                'invoice' => $invoice,
                'customer'=>$order->customer
            ];
    }
    
    private function generateInvoice($array_params)
    {
        // set timezone
        date_default_timezone_set("Asia/Manila");

        // create invoice
        $invoice = new Invoice;
        $invoice->number = str_random(4);
        $invoice->order_number = $array_params['order']['number'];
        $invoice->first_name = $array_params['order']['customer']['first_name'];
        $invoice->last_name = $array_params['order']['customer']['last_name'];
        $invoice->subtotal = str_replace(',', '', $array_params['order']['subtotal']);
        $invoice->discount = str_replace(',', '', $array_params['order']['discount']);
        $invoice->shipping_fee = str_replace(',', '', $array_params['order']['shipping_cost']);
        $invoice->total = str_replace(',', '', $array_params['order']['total']);
        $invoice->payment_date = $array_params['order']['date_paid'];
        $invoice->created = date("Y-m-d H:i:s");
        $invoice->status = 'Paid';
        $invoice->save();

        $updateInvoice = Invoice::where('number', $invoice->number)->first();
        $updateInvoice->number = str_pad($updateInvoice->id, 6, '0', STR_PAD_LEFT);
        $updateInvoice->update();

        // save invoice products
        foreach ($array_params['order']['orderProducts'] as $item)
        {
            $invoiceProd = new InvoiceProduct;
            $invoiceProd->invoice_number = $updateInvoice->number;
            $invoiceProd->inventory_sku = $item['inventory_sku'];
            $invoiceProd->product_name = $item['product_name'];
            $invoiceProd->price = str_replace(',', '', $item['price']);
            $invoiceProd->quantity = $item['quantity'];
            $invoiceProd->total = str_replace(',', '', $item['total']);
            $invoiceProd->shipping_rate = str_replace(',', '', $item['shipping_rate']);
            $invoiceProd->shipping_fee = str_replace(',', '', $item['shipping_fee']);
            $invoiceProd->invoicing_id = $item['ordering_id'];
            $invoiceProd->invoicing_type = $item['ordering_type'];
            $invoiceProd->date_invoice = date('Y-m-d');
            $invoiceProd->save();
        }
    }  

    public function requestNotViewed()
    {
        $cancel_request_count = CancelOrderRequest::where('viewed','=',0)->count();

        return response()->json(['count' => $cancel_request_count]);
    } 

    public function updateViewedStatus(CancelOrderRequest $cancel)
    {
        $cancel->viewed = 1;
        $cancel->update();

        return response()->json(['success' => true]);
    }
    
}
