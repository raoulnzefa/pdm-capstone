<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Reason;
use App\Models\CancelOrderRequest;
use App\Models\ReturnRequest;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\OrderProduct;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;

class CustomerController extends Controller
{
    private $cust_id;

    public function __construct()
    {
    	$this->middleware('auth:customer');
    }

    public function index()
    {
        return view('frontend.customer.my_account')->with('data', 'My Account');
    }

    public function orderSubmitted()
    {
    	return view('frontend.customer.order_submitted');
    }

    public function orderDetails($order)
    {
        // $invoice = Invoice::where('order_id', $order)->first();
        $previous_url = url()->previous();

        $order_detail = Order::where('number',$order)->with(
                            'orderProducts.inventory.product',
                            'customer',
                            'invoice.invoiceProducts',
                            'cancelOrderRequest',
                            'returnRequest',
                            'bankDepositSlip',
                            'storePickup'
                        )->first();

         // set timezone
        date_default_timezone_set("Asia/Manila");

        // $dt = Carbon::now();
        // $dateToday = $dt->toDateString();

        $returnDate = 0;
        
        if (!is_null($order_detail->order_warranty))
        {
            $return_date = strtotime($order_detail->order_warranty);   

            // check if return_until date is already passed

            if ($return_date < time())
            {
                $returnDate = 1;
            }

        }

    	return view('frontend.customer.order_details')
                ->with([
                    'order' => $order_detail,
                    'previous_url'=>$previous_url,
                    'data'=>'Order Details',
                    'isReturnDatePassed' => $returnDate,
                ]);
    }

    public function changePass()
    {
        return view('frontend.customer.change_pass')->with('data','Change Password');
    }
    public function viewInvoice(Order $order)
    {
        return view('frontend.customer.invoice_page')->with(['order'=>$order]);
    }

    public function returnRequest($order)
    {
        $order_data = Order::where('number', $order)->with('orderProducts.inventory.product')->first();

        if ($order_data->returnRequest)
        {
            $previous_url = url()->previous();

            return redirect(url($previous_url));
        }

        $reasons = Reason::where('type','Return')->get();

        $invoice = Invoice::where('order_number','=', $order)
                    ->where(function($query) {
                        $query->where('status','!=','Void');
                    })->first();

        $data = 'Return Request';

        $actions = ['Replacement (Same product)'];

        return view('frontend.order.request_return_form', compact('order_data', 'invoice','data', 'reasons', 'actions'));
    }

    public function returnRequestsList()
    {
        $cust_id = Auth::guard('customer')->user()->id;
        $return_request_list = ReturnRequest::where('customer_id',$cust_id)->get();

        return view('frontend.customer.return_request_list')->with(['return_requests'=>$return_request_list, 'data'=>'Returns']);
    }

    public function cancellationList()
    {
        $cust_id = Auth::guard('customer')->user()->id;
        $cancellation_list = CancelOrderRequest::where('customer_id', $cust_id)->get();

        return view('frontend.customer.cancellation_list')->with(['cancellation_list'=>$cancellation_list, 'data'=>'Cancellation']);
    }

    public function cancelledOrders()
    {
        return view('frontend.customer.cancelled_orders');
    }

    

}
