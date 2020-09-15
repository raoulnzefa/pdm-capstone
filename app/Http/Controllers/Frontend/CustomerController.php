<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Reason;
use App\Models\ReplacementRequest;
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
                            'replacementRequest',
                            'bankDepositSlip',
                            'storePickup'
                        )->first();

        if ($order_detail->status_update > 0)
        {
            $order_detail->status_update = 0;
            $order_detail->update();
        }

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

}
