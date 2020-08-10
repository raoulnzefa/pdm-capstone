<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Traits\UserLogs;
use App\Http\Controllers\Traits\InventoryManager;
use App\Notifications\PendingStatusNotification;
use App\Notifications\CancelOrderNotif;
use App\Notifications\OrderPickedUpNotification;
use App\Notifications\PaymentReceived;
use App\Notifications\DeliveryConfirmation;
use App\Notifications\DeliverOrderNotification;
use App\Customer;
use App\Order;
use App\OrderProduct;
use App\Invoice;
use App\InvoiceProduct;
use App\PaypalPayment;
use App\Inventory;
use App\CancelOrderRequest;
use App\CancelProductRequest;
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
use DB;
use Carbon\Carbon;
use App\BankDepositSlip;
use App\BankDepositPayment;

class OrderController extends Controller
{
    use UserLogs, InventoryManager;

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

    public function viewOrders(Request $request)
    {  
        $today = Carbon::today();
        // chechk query parameter
        if ($request->query('search'))
        {
            // search value
            $search_val = $request->query('search');
            // get orders base on order number
            $orders = Order::where('number',$search_val)
                            ->with('customer')->paginate(10);

            // appends to pagination
            $orders->appends($request->only('search'));
        }
        elseif ($request->query('from') && $request->query('to'))
        {
            // set timezone
            date_default_timezone_set("Asia/Manila");
            // set variables
            $from_date = $request->query('from');
            $to_date = $request->query('to');

            $orders = Order::whereBetween('date_order', [$from_date, $to_date])
                            ->with('customer')
                            ->paginate(10);

            //$orders->appends($request->only(['from', 'to']));
        }
        elseif ($request->query('status'))
        {
            // order status
            $order_status = $request->query('status');
            // get orders
            $orders = Order::where('status', $order_status)
                            ->with('customer')->paginate(10);

            //$orders->appends($request->only('status'));
        }
        else
        {

             $orders = Order::orderBy('viewed','asc')
                    ->where(function($query) {
                        $query->where('status', '!=', 'Shipped')
                            ->Where('status', '!=', 'Cancelled')
                            ->Where('status','!=','Picked up')
                            ->Where('status', '!=', 'Completed');
                    })
                    ->with('customer')->paginate(10);

            // $orders = Order::orderBy('number','desc')
            //         ->where('status', '!=', 'Shipped')
            //         ->Where('status', '!=', 'Cancelled')
            //         ->Where('status', '!=', 'Replaced')
            //         ->Where('status', '!=', 'Refunded')
            //         ->where(function($query) {
            //             $query->orderBy('viewed', 'asc'));
            //         })
            //         ->with('customer')->paginate(10);

        }
    

    	return response()->json($orders);
    }

    public function customerOrders(Customer $customer)
    {
    	return response()->json($customer->orders);
    }

    public function customerIncOrders($customer)
    {
    	$incomplete_orders = Order::where('customer_id', $customer)->where('status', '!=','Completed')->get();

    	return response()->json($incomplete_orders);						
    }

    public function orderDetails($order)
    {
        $orderDetail = Order::where('number',$order)->with(
            'orderProducts.inventory.product',
            'customer',
            'invoice.invoiceProducts',
            'cancelOrderRequest',
            'returnRequest',
            'returnedOrder.returnedProducts.inventory.product',
            'bankDepositSlip'
        )->first();

        $first_invoice = Invoice::where('order_number', '=', $order)->first();

        date_default_timezone_set("Asia/Manila");

        $date_now = date('m/d/Y');
        
        $dateReturnUntil = Carbon::parse($orderDetail->return_until);

        if ($dateReturnUntil->isPast())
        {
            $expired = true;
        }
        else
        {
            $expired = false;
        }
        
        $total_qty = OrderProduct::where('order_number', $orderDetail->number)->sum('quantity');

    	return response()->json([
            'order'=>$orderDetail,
            'total_qty'=>$total_qty,
            'date_now'=>$date_now,
            'invoice'=> $first_invoice,
            'expired' => $expired
        ]);
    }

    public function shipOrder(Request $request)
    {
        

        date_default_timezone_set("Asia/Manila");


        $d = 7;

        $return_date = strftime("%Y-%m-%d %T", strtotime("+$d weekday"));

        $order = Order::where('number', $request->order_id)->first();
        $order->status = 'Shipped';
        $order->date_shipped = date('Y-m-d H:i:s');
        $order->return_until = $return_date;
        $order->update();
        
        // update order product status to Shipped
        foreach ($order->orderProducts as $item)
        {
            $orderProd = OrderProduct::where('id', $item['id'])->first();
            
            if ($orderProd->status != 'Cancelled')
            {
                $orderProd->status = 'Shipped';
            }

            $orderProd->update();
        }   

        $customer = Customer::where('id', $order->customer_id)->first(); 

        try {

            $customer->notify(new DeliveryConfirmation($order));


            $array_params = [
                'id' => $request->admin_id,
                'action' => 'Ship order. Order Number: '.$order->number.'.'
            ];

            $this->createUserLog($array_params);

            $response = ['success' => true];

        } catch(Exception $e) {

            $response = ['success' => false, 'message' => $e->getMessage()];

        }

        return response()->json($response);
    }

    public function deliverOrder(Request $request, Order $order)
    {
        $days_warranty = 7;
        
        $order->status = 'Delivered';
        $order->order_warranty = '';

    }

    public function customerOrderStatus($customer)
    {
        $order_status = Order::where('customer_id', $customer)
                                ->where(function($query) {
                                    $query->where('status', 'Reserved')
                                        ->orWhere('status', 'Failed')
                                        ->orWhere('status', 'Processing');
                                })
                                ->orderBy('id','desc')->get();
        
        return response()->json($order_status);
    }

    public function customerOrderHistory($customer)
    {
       $order_completed = Order::where('customer_id', $customer)
                                ->where(function($query) {
                                    $query->where('status','Shipped')
                                        ->orWhere('status','Picked up')
                                        ->orWhere('status','Cancelled')
                                        ->orWhere('status','Completed');
                                })
                                ->orderBy('id','desc')->get();

        return response()->json($order_completed);
    }

    public function orderInvoice(Order $order)
    {
        //$order->invoice;
        $order->orderProducts;

        return response()->json($order);
    }

    public function markAsPaid(Request $request)
    {
        date_default_timezone_set("Asia/Manila");

        $order = Order::where('number', $request->order_id)->first();

        $response = [];

        $d = 7;

        $return_date = strftime("%Y-%m-%d %T", strtotime("+$d weekday"));

        if ($order->shipping_method == 'Shipping')
        {
            try
            {
                 //delivery working days....
                $d_delivery = 3;

                $date = strftime("%A, %B %d, %Y", strtotime("+$d_delivery weekday"));
                $days = $d_delivery;

                $date_data = ['date'=>$date, 'days'=> $days];

                $customer = Customer::where('id', $request->customer_id)->first();

                $order->status = 'Processing';
                $order->payment_status = 'Paid';
                $order->return_until = $return_date;
                $order->date_shipped = date('Y-m-d H:i:s');
                $order->update();
                
                $array_params = [
                    'id' => $request->admin_id,
                    'action' => 'Update payment status to Paid. Order Number: '.$order->number.', Payment Method: '.$order->payment_method.'.'
                ];

                $this->createUserLog($array_params);

                $sales_params = ['order'=>$order];

                $this->createInvoice($sales_params);

                //$this->createSales($sales_params);

                // send  email payment received
                $customer->notify(new PaymentReceived($order, $date_data));

                $response = ['success' => true];
            }
            catch(Exception $ex)
            {
                $response = ['success' => false, 'message' => $ex->getMessage()];
            }

        }
        else
        {
             // update order status
            $order->status = 'Picked up';
            $order->payment_status = 'Paid';
            $order->date_paid = date('Y-m-d');
            $order->status_update = 1;
            $order->return_until = $return_date;

             $order->update();

            $array_params = ['order'=>$order];

            $this->createInvoice($array_params);

            //$this->createSales($array_params);

            $array_params = [
                'id' => $request->admin_id,
                'action' => 'Mark as Paid. Order Number: '.$order->number.', Payment Method: '.$order->payment_method.'.'
            ];

            $this->createUserLog($array_params);

            $response = ['success' => true];

        }

       
    

        return response()->json($response);
    }

    public function cashMarkAsPaid(Request $request, Order $order)
    {
        date_default_timezone_set("Asia/Manila");

        $d = 7;

        $return_date = strftime("%Y-%m-%d %T", strtotime("+$d weekday"));

        $order->status = 'Picked up';
        $order->payment_status = 'Paid';
        $order->status_update = 1;
        $order->return_until = $return_date;
        $order->update();

        $array_params = ['order'=> $order];

        $this->createInvoice($array_params);

        //$this->createSales($array_params);

        return response()->json(['success' => true]);
    }  

    public function pickedUp(Request $request, Order $order)
    {
        date_default_timezone_set("Asia/Manila");

        $d = 7;

        $return_date = strftime("%Y-%m-%d %T", strtotime("+$d weekday"));

        // order is paid

        $order->status = 'Picked up';
        $order->date_completed = date('Y-m-d H:i:s');
        $order->return_until = $return_date;
        $order->update();

        return response()->json(['success' => true]);
    }    

    public function ordersToday()
    {
        date_default_timezone_set("Asia/Manila");

        $today = date('Y-m-d');

        $orders_today = Order::whereDate('created_at', $today)->count();

        return response()->json($orders_today);
    }
    
    public function customerCancelledOrders($customer)
    {
        $cust_cancelled_orders = Order::where('customer_id', $customer)
                                        ->where('status', 'Cancelled')
                                        ->get();

        return response()->json($cust_cancelled_orders);
    }

    public function cancelOrder(Request $request, Order $order)
    {
        // set timezone
        date_default_timezone_set("Asia/Manila");
        // cancel order
        $order->status = "Cancelled";
        $order->payment_status = "Cancelled";
        $order->date_cancelled = date('Y-m-d');
        $order->update();

        $order_products = $order->orderProducts;

        // restock product
        foreach ($order_products as $item) {

            $restockInventory = Inventory::where('sku', $item->inventory_sku)->first();
            $restockInventory->quantity += $item->quantity;
            $restockInventory->update();

            $item->status = "Cancelled";
            $item->update();
        }

        $customer = Customer::where('id', $order->customer_id)->first();

        // send cancellation email confirmation
        try
        {
            $customer->notify(new CancelOrderNotif($order));
            $response = ['success' => true];
        }
        catch(Exception $e)
        {
            $response = ['success'=>false, 'err_msg' => $e->getMessage()];
        }

        return response()->json($response);
    }

    public function bestSellingProducts()
    {
        $best_selling = OrderProduct::select(DB::raw('inventory_sku, product_name,  SUM(quantity) AS total_qty, SUM(total) AS total_amount'))
                        ->where('status', '!=', 'Cancelled')
                        ->groupBy('inventory_sku', 'product_name')
                        ->orderBy('total_qty', 'DESC')
                        ->having('total_qty', '>', 4)
                        ->limit(5)
                        ->get();

        return response()->json($best_selling);
    }

    public function sendEmailForPending(Request $request, Order $order)
    {
        // get customer data
        $customer = Customer::where('id', $order->customer_id)->first();

        try
        {
            // send email
            $customer->notify(new PendingStatusNotification($order));

            $response = ['success' => true];
        }
        catch(Exception $e)
        {
            $response = ['success' => false];
        }  

        return response()->json($response);
    }

    private function createInvoice($array_params)
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
            $invoiceProd->invoicing_id = $item['ordering_id'];
            $invoiceProd->invoicing_type = $item['ordering_type'];
            $invoiceProd->date_invoice = date('Y-m-d');
            $invoiceProd->save();
        }
    }

    public function orderNotview()
    {
        try
        {
            $order_not_viewed = Order::where('viewed','=',0)->count();

            $response = ['count'=> $order_not_viewed];
        }
        catch(Exception $e)
        {
            $response = ['err' =>$e->getMessage()];
        }

        return response()->json($response);
        
    }
    
    public function updateOrderViewed(Order $order)
    {
        $order->viewed = 1;
        $order->update();

        return response()->json(['success'=>true]);
    }

    public function notCompletedOrders()
    {
        $orders = Order::where('status', '!=', 'Shipped')
                        ->where('status', '!=', 'Picked up')
                        ->count();


        return response()->json($orders);
    }

    public function saveShipOrder(Request $request)
    {
        date_default_timezone_set("Asia/Manila");

        $customer = Customer::where('id', $request->customer_id)->first();

        $response = [];

        $d = 7;

        $return_date = strftime("%Y-%m-%d %T", strtotime("+$d weekday"));

        $order = Order::where('number', $request->order_number)->first();
        $order->status = 'Shipped';
        $order->return_until = $return_date;
        $order->date_shipped = date('Y-m-d H:i:s');
        $order->update();
        
        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Update status to Shipped. Order Number: '.$order->number.', Payment Method: '.$order->payment_method.'.'
        ];

        $this->createUserLog($array_params);

        try
        {
            $customer->notify(new DeliveryConfirmation($order));
            $response = ['success' => true];
        }
        catch (Exception $e)
        {
            $response = ['success'=>false, 'err_msg' => $e->getMessage()];
        }

        return response()->json($response);
    }

    public function bankDepositShipping($order_id)
    {
        try {

            $order = Order::where('number', $order_id)->first();

            $customer = Customer::where('id', $order->customer_id)->first();
            
            // set time zone
            date_default_timezone_set("Asia/Manila");

            $has_deposit_slip = BankDepositSlip::where('order_number', $order_id)->count();

            if ($has_deposit_slip > 0)
            {
                $bank_deposit_slip = BankDepositSlip::where('order_number', $order_id)->first();

                if ($bank_deposit_slip->is_confirmed == 1)
                {
                    // update order status
                    $order->status = 'Processing';
                    $order->payment_status = 'Paid';
                    $order->date_paid = date('Y-m-d');
                    $order->status_update = 1;
                    $order->update();

                    $array_params = ['order'=>$order];

                    $this->createInvoice($array_params);

                    //$this->createSales($array_params);

                    $d = 3;

                    $estimated_date = strftime("%A, %B %d, %Y", strtotime("+$d weekday"));

                    $email_date = ['date'=>$estimated_date, 'days'=>$d];

                    //no internet
                    //$customer->notify(new PaymentReceived($order, $email_date));

                    $array_params = [
                        'id' => $request->admin_id,
                        'action' => 'Mark as Paid. Order Number: '.$order->number.', Payment Method: '.$order->payment_method.'.'
                    ];

                    $this->createUserLog($array_params);

                    $response = ['success' => true];
                }
                else
                {
                    $response = ['success' => false, 'message'=>'Please confirm the bank deposit slip'];
                }
            }
            else
            {
                $response = ['success' => false, 'message'=>'No Bank Deposit Slip'];
            }

        } catch (Exception $e) {

            $response = ['error' => $e->getMessage() ];

        }

        return $response;
    }

}
