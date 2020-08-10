<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Traits\UserLogs;
use App\Order;
use App\OrderProduct;
use App\Customer;
use App\Inventory;
use App\ReturnRequest;
use App\ReturnProductRequest;
use App\ReturnedOrder;
use App\ReturnedProduct;
use App\Invoice;
use App\Notifications\ReturnRefundApprovedNotif;
use App\Notifications\ProductReplacementNewOrderNotif;
use App\Notifications\ReturnReplacementApprovedNotif;
use App\Notifications\DeclinedReturnRequestNotif;
use App\Notifications\RefundReturnNotif;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PaypalPayment;
use PayPal\Api\Amount;
use PayPal\Api\RefundRequest;
use PayPal\Api\Sale;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Exception\PayPalConnectionException;
use Exception;
use Config;

class ReturnRequestController extends Controller
{
    use UserLogs;

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

    public function storeReturnRequest(Request $request)
    {
        //return response()->json($request->all());
    	$request->validate([
    		'reason' => 'required'
    	]);

    	// set time zone
    	date_default_timezone_set("Asia/Manila");

    	// save return request
    	$returnRequest = new ReturnRequest;
    	$returnRequest->customer_id = $request->customer_id;
        $returnRequest->invoice_number = $request->invoice_number;
    	$returnRequest->order_number = $request->order_number;
    	$returnRequest->reason_id = $request->reason;
        $returnRequest->action = $request->action;

    	if ($request->filled('comment'))
    	{
    		$returnRequest->comment = $request->comment;
    	}

    	$returnRequest->date_return_request = date('Y-m-d H:i:s');
    	$returnRequest->status = 'Pending';
    	$returnRequest->save();

    	// save return product
    	foreach ($request->return_product_list as $item) {
    		
            $orderProd = OrderProduct::where('id', $item['id'])->first();

            if (!$orderProd->cancelledProduct)
            {
                if ((int)$item['return_qty'] > 0)
                {
                    $returnProd = new ReturnProductRequest;
                    $returnProd->return_request_id = $returnRequest->id;
                    $returnProd->order_product_id = $item['id'];
                    $returnProd->quantity = (int)$item['return_qty'];
                    $returnProd->ordered_qty = (int)$item['quantity'];
                    $returnProd->amount = $returnProd->quantity * str_replace(',', '', $item['price']);
                    $returnProd->save();
                }
            }
            else
            {
                if ((int)$item['return_qty'] > 0)
                {
                    $returnProd = new ReturnProductRequest;
                    $returnProd->return_request_id = $returnRequest->id;
                    $returnProd->order_product_id = $item['id'];
                    $returnProd->quantity = (int)$item['return_qty'];
                    $returnProd->ordered_qty = (int)$item['quantity'];
                    $returnProd->amount = $returnProd->quantity * str_replace(',', '', $item['price']);
                    $returnProd->save();
                }
            }
    	}


    	return response()->json(['success' => true]);
    }

    public function returnRequestList()
    {
    	$return_request_list = ReturnRequest::orderBy('created_at', 'desc')->with('customer')->paginate(10);

    	return response()->json($return_request_list);
    }

    public function returnRequestDetails($return_request)
    {
        $return_request_details = ReturnRequest::where('id', $return_request)
                                    ->with(
                                        'returnProductRequests.orderProduct.inventory.product',
                                        'reason',
                                        'order'
                                    )->first();

       $total_refund = ReturnProductRequest::where('return_request_id', $return_request)->sum('amount');

        $order = Order::where('number', $return_request_details->order->number)
                        ->with('customer')->first();

        
        if ($return_request_details->action == 'Refund')
        {

            $response = $this->checkOrderQuantityDiscount($order, $return_request, $total_refund, $return_request_details);
                                
        }
        else
        {
            
           $response = $this->checkOrderQuantityDiscount($order, $return_request, $total_refund, $return_request_details);
        }
        

        return response()->json($response);
    }

    public function updateReturnRequestStatus(Request $request, ReturnRequest $return_request)
    {
        // set time zone
        date_default_timezone_set("Asia/Manila");

        // get customer
        $customer = Customer::where('id', $return_request->customer_id)->first();

        $amount_details = array(
                'subtotal' => number_format($request->subtotal, 2),
                'discount' => number_format($request->discount, 2),
                'shipping_fee' => number_format($request->shipping_fee, 2),
                'total' => number_format($request->total, 2)
            );

        // check return status if approve
        if ($request->return_status == 'Approve')
        {             
            try
            {
                if ($return_request->action == 'Replacement')
                {
                    $status = 'Approved';
                    $customer->notify(new ReturnReplacementApprovedNotif($return_request, $amount_details));
                    $response = ['success' => true];
                }
                else
                {
                    $status = 'Awaiting Refund';
                    $customer->notify(new ReturnRefundApprovedNotif($return_request , $amount_details));
                    $response = ['success' => true];
                }

                // $return_params = [
                //     'id' => $request->admin_id,
                //     'action' => 'Approved a return request. Order Number: '.$return_request->order_number.'.'
                // ];

                // $this->createUserLog($return_params);
               
            }
            catch(Exception $ex)
            {
                $response = ['success'=>false, 'err_msg'=>$ex->getMessage()];
            }
            
        }
        else
        {
            //return response()->json($request->decline_message);
            // if not approve
            $message = $request->decline_message;
            try
            {
                $status = 'Declined';
                $customer->notify(new DeclinedReturnRequestNotif($return_request, $amount_details, $message));
                
                // $return_params = [
                //     'id' => $request->admin_id,
                //     'action' => 'Declined a return request. Order Number: '.$return_request->order_number.'.'
                // ];

                // $this->createUserLog($return_params);

                // $response = ['success' => true];
            }
            catch(Exception $ex)
            {
                $response = ['success'=>false, 'err_msg'=>$ex->getMessage()];
            }
        }

        $return_request->status = $status;
        $return_request->update();

       
        return response()->json($response);
    }

    public function refundReturn(Request $request, ReturnRequest $return_request)
    {
        // set timezone
        date_default_timezone_set("Asia/Manila");

        // get order details
        $order = Order::where('number', $return_request->order_number)->first();

        // set total refund
        $total_refund = str_replace(',', '', $request->refund_amount);

        // set status if full refund or not
        $status = 'Refunded';
        $partially_refunded = 'Partially Refunded';

        if ($order->payment_method == 'PayPal')
        {
            // get paypal payment details
            $saleId = PaypalPayment::where('order_number', $return_request->order_number)
                                    ->first()->transaction_id;
                                    
            $amount = new Amount();
            $amount->setCurrency('PHP')
                ->setTotal($total_refund);

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
                    $return_request->status = $status;
                    $return_request->update();

                    // update order status
                    $order->status = $status;
                    // $order->payment_status = $status;
                    $order->update();
                    //$response = ['success'=>true, 'state'=> $refundedSale->getState()];
                }
            }
            catch(PayPalConnectionException $e)
            {
                return response()->json(['success' => false, 'err_msg'=> $e->getData()]);
            }
        }
        elseif ($order->payment_method == 'Bank Deposit')
        {
            // update return status 
            $return_request->status = $status;
            $return_request->update();

            // update order status 
            $order->status = $status;
            // $order->payment_status = $status;
            $order->update();
        }
        elseif ($order->payment_method == 'Cash')
        {
            // update return status
            $return_request->status = $status;
            $return_request->update();

            // update order status 
            $order->status = $status;
            // $order->payment_status = $status;
            $order->update();
        }

        // check if restock the product
        if ($request->restock_product == 'yes')
        {
            foreach ($return_request->returnProductRequests as $item)
            {
                $inventory = Inventory::where('sku', $item['orderProduct']['inventory_sku'])->first();

                $inventory->quantity += $item['quantity'];
                $inventory->availability = 'In stock';
                $inventory->update();
            }
        }
        // get total refund amount of return products
        $product_refund = ReturnProductRequest::where('return_request_id', $return_request->id)->sum('amount');

        // save return details
        $returned_params = array(
            'return_request'=>$return_request,
            'subtotal'=>$request->subtotal,
            'discount'=>$request->discount,
            'shipping_fee'=>$request->shipping_fee,
            'total' => $total_refund
        );

        $this->saveReturnedOrder($returned_params);

        $invoiceInThisOrder = Invoice::where('order_number', $return_request->order_number)->count();

        if ($invoiceInThisOrder > 1)
        {
            $invoice_num = Invoice::where('order_number', $return_request->order_number)
                            ->orderBy('created', 'asc')
                            ->first()->number;
        }
        else
        {
            $invoice_num = Invoice::where('order_number', $return_request->order_number)
                            ->orderBy('created', 'desc')
                            ->first()->number;
        }

        // create sales return
        $array_params = array(
            'order' => $return_request->order,
            'invoice_number' => $invoice_num,
            'type'=> $return_request->action,
            'subtotal'=> $request->subtotal,
            'discount' => $request->discount,
            'shipping_fee' => $request->shipping_fee,
            'total'=> $total_refund,
            'products'=> $return_request->returnProductRequests
        );

        $this->createSalesReturn($array_params);

        // send email
        try
        {
            $amount_details = array(
                'subtotal' => number_format($request->subtotal, 2),
                'discount' => number_format($request->discount, 2),
                'shipping_fee' => number_format($request->shipping_fee, 2),
                'total' => number_format($request->refund_amount, 2)
            );
            // get customer details
            $customer = Customer::where('id', $return_request->customer_id)->first();

            // send email
            $customer->notify(new RefundReturnNotif($return_request, $amount_details));

            // $return_params = [
            //     'id' => $request->admin_id,
            //     'action' => 'Created a refund. Invoice Number: '.$invoice_num.'.'
            // ];

            // $this->createUserLog($return_params);

            $response = ['success' => true];
        }
        catch(Exception $ex)
        {
            $response = ['success' => false, 'err_msg'=>$ex->getMessage()];
        }  

        return response()->json($response);
    }

    public function replaceProduct(Request $request, ReturnRequest $return_request)
    {
        $insufficient_stock = false;
        $new_order_number = '';

        foreach ($return_request->returnProductRequests as $item)
        {
            $inventory = Inventory::where('sku', $item['orderProduct']['inventory_sku'])->first();   
            if ($inventory->quantity < $item['quantity'])
            {
                $insufficient_stock = true;
            }
        }

        // if  true insufficient stock
        if ($insufficient_stock)
        {
            $response = ['success' => false, 'err_msg' => 'Insufficient stock' ];
        }
        else
        {
            // get total return
            $total = ReturnProductRequest::where('return_request_id', $return_request->id)->sum('amount');
            // save returned order
            $returned_params = array(
                'return_request'=> $return_request,
                'subtotal'=>$request->subtotal,
                'discount'=>$request->discount,
                'shipping_fee'=>$request->shipping_fee,
                'total' => $total
            );
            // save returned order and product
            $this->saveReturnedOrder($returned_params);
            
            $array_params = array(
                'order' => $return_request->order,
                'invoice' => $return_request->order->invoice,
                'type'=> $return_request->action,
                'subtotal'=> $request->subtotal,
                'discount' => $request->discount,
                'shipping_fee' => $request->shipping_fee,
                'total'=> $total,
                'products'=> $return_request->returnProductRequests
            );

            $this->createSalesReturn($array_params);

            // update order details
            $order = Order::where('number', $return_request->order->number)->first();
            $order->status = 'Replaced';
            $order->update();

            $return_request->status = 'Replaced';
            $return_request->update();
            // send email confimation for replacement that contains estimated delivery date
            // create new order for replacement

            $new_order_details = $this->generateNewOrderReplacement($return_request->order);

            try
            {
                $customer = Customer::where('id', $new_order_details->customer_id)->first();
                // send new order for replacement
                $customer->notify(new ProductReplacementNewOrderNotif($new_order_details));
                
                // $return_params = [
                //     'id' => $request->admin_id,
                //     'action' => 'Created a replacement. New Order Number: '.$new_order_details->number.'.'
                // ];

                // $this->createUserLog($return_params);

                $response = ['success' => true, 'new_order'=>$new_order_details->number];
            }
            catch(Exception $ex)
            {
               $response = ['success' => false, 'err_msg' => $ex->getMessage()];
            }


        }

        return response()->json($response);
    }

    public function customerReturnRequestList($customer)
    {
        // get return request list
        $customer_return_requests = ReturnRequest::where('customer_id', $customer)
                        ->with('reason', 'returnProductRequests.orderProduct.inventory.product','order')
                        ->orderBy('id', 'desc')->get();

        return response()->json($customer_return_requests);
    }

    private function generateNewOrderReplacement($order)
    {
        $return_qty = ReturnProductRequest::where('return_request_id', $order->returnRequest->id)->sum('quantity');

        $subtotal = ReturnProductRequest::where('return_request_id', $order->returnRequest->id)->sum('amount');

        // set time zone
        date_default_timezone_set("Asia/Manila");

        $new_order = new Order;
        $new_order->number = str_random(5);
        $new_order->customer_id = $order->customer_id;
        $new_order->first_name = $order->first_name;
        $new_order->last_name = $order->last_name;
        $new_order->street = $order->street;
        $new_order->province = $order->province;
        $new_order->municipal = $order->municipal;
        $new_order->barangay = $order->barangay;
        $new_order->zip_code = $order->zip_code;
        $new_order->company = $order->company;
        $new_order->phone_no = $order->phone_no;
        $new_order->quantity = $return_qty;
        $new_order->subtotal = $subtotal;
        $new_order->total = $subtotal;
        $new_order->shipping_method = $order->shipping_method;
        $new_order->payment_method = $order->payment_method;
        $new_order->payment_status = 'Paid';
        $new_order->status = 'Processing';
        $new_order->date_order = date('Y-m-d');
        $new_order->time_order = date('H:i:s');
        $new_order->date_created = date('Y-m-d H:i:s');
        $new_order->payment_status = 'Paid';

        if ($order->shipping_method == 'Shipping')
        {
            $d_delivery = 3;
            //db format
            $estimated_date = strftime("%Y-%m-%d", strtotime("+$d_delivery weekday"));
            $new_order->estimated_delivery_date = $estimated_date;
        }

        $new_order->remarks = 'Replacement';
        $new_order->save();

        $updateNewOrderNumber = Order::where('number', $new_order->number)->first();
        $updateNewOrderNumber->number = str_pad($updateNewOrderNumber->id, 6, '0', STR_PAD_LEFT);
        $updateNewOrderNumber->update();

        // save replace product
        foreach ($order->returnRequest->returnProductRequests as $item)
        {
            $newOrderProduct = new OrderProduct;
            $newOrderProduct->order_number = $updateNewOrderNumber->number;
            $newOrderProduct->inventory_sku = $item['orderProduct']['inventory_sku'];
            $newOrderProduct->product_name = $item['orderProduct']['product_name'];
            $newOrderProduct->price = str_replace(',', '',$item['orderProduct']['price']);
            $newOrderProduct->quantity = $item['quantity'];
            $newOrderProduct->shipping_rate = str_replace(',', '', $item['orderProduct']['shipping_rate']);
            $newOrderProduct->shipping_fee = str_replace(',', '', $item['orderProduct']['shipping_fee']);
            $newOrderProduct->total = str_replace(',', '', $item['amount']);
            $newOrderProduct->ordering_id = $item['orderProduct']['ordering_id'];
            $newOrderProduct->ordering_type = $item['orderProduct']['ordering_type'];
            $newOrderProduct->status = 'Reserved';
            $newOrderProduct->save();

            $inventory = Inventory::where('sku', $item['orderProduct']['inventory_sku'])->first();
            $inventory->quantity -= $item['quantity'];
            $inventory->update();

        }

        
        return $updateNewOrderNumber;
    }

    public function saveReturnedOrder($returned_params)
    {
        // set time zone
        date_default_timezone_set("Asia/Manila");

        $returnedOrder = new ReturnedOrder;
        $returnedOrder->invoice_number = $returned_params['return_request']['invoice_number'];
        $returnedOrder->order_number = $returned_params['return_request']['order_number'];
        $returnedOrder->type = $returned_params['return_request']['action'];
        $returnedOrder->subtotal = str_replace(',', '', $returned_params['subtotal']);
        $returnedOrder->discount = str_replace(',', '', $returned_params['discount']);
        $returnedOrder->shipping_fee = str_replace(',', '', $returned_params['shipping_fee']);
        $returnedOrder->total = str_replace(',', '', $returned_params['total']);
        $returnedOrder->return_created = date('Y-m-d H:i:s');
        $returnedOrder->save();

        // save return products
        foreach ($returned_params['return_request']['returnProductRequests'] as $item)
        {
            $returnedProd = new ReturnedProduct;
            $returnedProd->returned_order_id = $returnedOrder->id;
            $returnedProd->inventory_sku = $item['orderProduct']['inventory_sku'];
            $returnedProd->price = str_replace(',', '', $item['orderProduct']['price']);
            $returnedProd->quantity = $item['quantity'];
            $returnedProd->amount = str_replace(',', '', $item['amount']);
            $returnedProd->save();
        }

    }

    private function checkOrderQuantityDiscount($order, $return_request, $total_refund, $return_request_details)
    {
        $date_requested = strtotime($return_request_details->date_return_request);

        $current_date = strtotime(date('Y-m-d H:i:s'));
        // getting the value of old date + 24 hours (86400)
        $returnDateRequest =  (int)$current_date - (int)$date_requested;

        if ((int)$returnDateRequest >= 86400)
        {
            $withdrawal_expired = true; 
        }
        else
        {
            $withdrawal_expired = false;
        }

        $discount = str_replace(',', '', $order->discount);

        $order_qty = OrderProduct::where('order_number','=',$order->number)->sum('quantity');
        
        $discount_per_product = $discount / $order_qty;

        $return_qty = ReturnProductRequest::where('return_request_id', '=', $return_request_details->id)->sum('quantity');

        $discount_deducted = $return_qty * $discount_per_product;

        $new_subtotal = $total_refund - $discount_deducted;

        $shipping_fee = 0;
        // if order is shippping it will be added to the total amount to refund
        // get the shipping rate per product
        

        $amount = $new_subtotal + $shipping_fee;

        return $response = array(
                'order'=> $order,
                'return_request_details' => $return_request_details,
                'subtotal' => $total_refund,
                'discount' => $discount_deducted,
                'shipping_fee' => $shipping_fee,
                'total_refund' => $amount,
                'withdrawal_expired' => $withdrawal_expired
            );
    }
    public function withdrawReturn($order)
    {
        $return_request = ReturnRequest::where('order_number', $order)->first();
        $return_request->status = 'Withdrawn';
        $return_request->update();

        return response()->json(['success'=>true]);
    }

    private function deductInventory()
    {

    }  

    private function computeProductQtyDiscount($order_qty, $return_qty)
    {

    }

    public function returnNotViewed()
    {
        $return_request_count = ReturnRequest::where('viewed','=',0)->count();

        return response()->json(['count'=>$return_request_count]);
    }

    public function updateReturnViewStatus(ReturnRequest $return)
    {
        $return->viewed = 1;
        $return->update();
        
        return response()->json(['success' => true]);
    }

}
