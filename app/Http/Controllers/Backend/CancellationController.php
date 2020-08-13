<?php

namespace App\Http\Controllers\Backend;

use App\Models\Order;
use App\Models\Customer;
use App\Models\CancelOrderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\ApprovedPaidCancellationNotif;
use App\Notifications\ApprovedNotPaidCancellationNotif;
use App\Notifications\DeclinedCancellationNotification;
use Exception;
use Carbon\Carbon;
use App\PaypalPayment;
use PayPal\Api\Amount;
use PayPal\Api\RefundRequest;
use PayPal\Api\Sale;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Exception\PayPalConnectionException;
use Config;
use App\Http\Controllers\Traits\UserLogs;

class CancellationController extends Controller
{
    use UserLogs;

    private $_api_context;

    public function __construct()
    {
        $this->middleware('auth:admin');

        $paypal_conf = Config::get('paypal');

        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret']
            
        ));

        $this->_api_context->setConfig($paypal_conf['settings']);

        // set timezone
        //date_default_timezone_set("Asia/Manila");
    }

    public function index()
    {
    	$cancellation = CancelOrderRequest::paginate(6);

    	return view('backend.cancellation.list', compact('cancellation'));
    }

    public function details(CancelOrderRequest $cancellation)
    {
        $cancellation->viewed = 1;
        $cancellation->update();
        
    	return view('backend.cancellation.details', compact('cancellation'));
    }

    public function declineForm(CancelOrderRequest $cancellation) {
        if ($cancellation->status == 'Declined')
        {
            return redirect()->route('cancellation_details',['cancellation'=>$cancellation->id]);
        }

        return view('backend.cancellation.decline_form')->with(['cancellation'=>$cancellation]);
    }

    public function submitDecline(Request $request) {

        $request->validate([
            'message' => 'required'
        ]);

        try
        {
            $cancellation = CancelOrderRequest::where('id', (int)$request->cancellation_id)->first();
            $customer = Customer::where('id', $cancellation->customer_id)->first();
            // send an decline email to customer.
            $customer->notify(new DeclinedCancellationNotification($cancellation, $request->message));
            //update cancellation request status to decline
            $cancellation->status = "Declined";
            $cancellation->update();
            // redirect to cancellation request details
            return redirect()->route('cancellation_details',['cancellation'=>$request->cancellation_id])->with('cancellation-request-succes', 'Cancellation request has been declined.');

        }
        catch (Exception $ex)
        {
            return redirect()->back()->with('decline-request-error', $ex->getMessage());
        }
    }

    public function approveRequest(Request $request) {        
        try
        {
            $cancellation = CancelOrderRequest::where('id', (int)$request->cancellation_id)->first();
            $customer = Customer::where('id', $cancellation->customer_id)->first();
            $status = "Approved";
            
            //update cancellation request status to decline
            $cancellation->status = $status;
            $cancellation->status_update = 1;
            $cancellation->update();

           
            $order = Order::where('number', $cancellation->order_number)->first();
            $order->status = "Cancelled";
            $order->update();

            if ($cancellation->order->payment_status == 'Paid')
            {
                $status = "Awaiting Refund";

                // send an decline email to customer.
                $customer->notify(new ApprovedPaidCancellationNotif($cancellation));
            }
            else
            {
                $customer->notify(new ApprovedNotPaidCancellationNotif($cancellation));
            }

            // redirect to cancellation request details
            return redirect()->route('cancellation_details', ['cancellation'=>$request->cancellation_id])->with('cancellation-request-success', 'Cancellation has been approved.');

        }
        catch (Exception $ex)
        {
            return redirect()->back()->with('c-request-error', $ex->getMessage());
        }
          
    }

    public function refundCancellation(Request $request) {
        
    }
}