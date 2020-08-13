<?php

namespace App\Http\Controllers\Backend;

use App\Notifications\ReturnRefundApprovedNotif;
use App\Notifications\ProductReplacementNewOrderNotif;
use App\Notifications\ReturnReplacementApprovedNotif;
use App\Notifications\DeclinedReturnRequestNotif;
use App\Notifications\ReturnRequestStatusNotif;
use App\Notifications\RefundReturnNotif;
use App\Http\Controllers\Traits\UserLogs;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ReturnRequest;
use App\Models\ReturnProductRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
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

class ReturnRequestController extends Controller
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
    	$returnRequests = ReturnRequest::paginate(5);

    	return view('backend.return_order.return_request_list', compact('returnRequests'));
    }

    public function details(ReturnRequest $returnRequest)
    {
    	// $admin = $this->getUser();
    	// $params = [
    	// 	'id' => $admin->id,
    	// 	'action' => 'Sample user log.',
     // 	];

     // 	$this->createUserLog($params);
    	$returnRequest->viewed = 1;
    	$returnRequest->update();

    	return view('backend.return_order.return_details', compact('returnRequest'));;
    }

    public function approveRequest(Request $request, ReturnRequest $returnRequest)
    {
    	// send email
    	try
    	{
            $customer = Customer::where('id', $returnRequest->customer_id)->first();
            
    		if ($returnRequest->action == 'Replacement')
            {
                $status = 'Approved';
                $customer->notify(new ReturnReplacementApprovedNotif($returnRequest));
                
            }
            else
            {
                $status = 'Awaiting Repair';
                $customer->notify(new ReturnRefundApprovedNotif($returnRequest));            
            }

            $returnRequest->status = $status;
            $returnRequest->update();
    	}
    	catch(Exception $ex)
    	{
    		return redirect()->back()->with('return-request-error', $ex->getMessage());
    	}

    	$admin = $this->getUser();
 
    	$params = [
    		'id' => $admin->id,
    		'action' => 'Approved return request. Order number: '.$returnRequest->order_number,
     	];

     	$this->createUserLog($params);


    	return redirect()->back()->with('return-request-success', 'Return request has been approved.');
    }

    public function declineRequestForm(ReturnRequest $returnRequest)
    {
    	return view('backend.return_order.decline_form', compact('returnRequest'));
    }

    public function declineRequest(Request $request, ReturnRequest $returnRequest)
    {
    	$request->validate([
    		'message' => 'required'
    	]);

    	// send email
    	try
    	{
    		$customer = Customer::where('id', $returnRequest->customer_id)->first();
    		$customer->notify(new DeclinedReturnRequestNotif($returnRequest, $request->message));

    		$returnRequest->status = 'Declined';
    		$returnRequest->update();
    	}
    	catch(Exception $ex)
    	{
    		return redirect()->back()->with('return-request-error', $ex->getMessage());
    	}

    	$admin = $this->getUser();
 
    	$params = [
    		'id' => $admin->id,
    		'action' => 'Declined return request. Order number: '.$returnRequest->order_number,
     	];

     	$this->createUserLog($params);

    	return redirect()
    			->route('return_request_details', ['returnRequest'=>$returnRequest])
    			->with('return-request-success', 'Return has been declined.');
    }

    public function refundRequest(Request $request, ReturnRequest $returnRequest)
    {
    	$total_refund = str_replace(',', '', $returnRequest->total);

    	$order = Order::where('number', $returnRequest->order_number)->first();

    	if ($returnRequest->order->payment_method == 'PayPal')
    	{
    		// get paypal payment details
            $saleId = PaypalPayment::where('order_number', $returnRequest->order_number)->first()->transaction_id;
                                    
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
                    $returnRequest->status = 'Refunded';
                    $returnRequest->update();

                    // update order status
                    $order->status = $status;
                    // $order->payment_status = $status;
                    $order->update();
                    //$response = ['success'=>true, 'state'=> $refundedSale->getState()];
                }

            }
            catch(PayPalConnectionException $e)
            {
            	//$e->getData();
            	return redirect()->back()->with('return-request-error', $e->getData());
            }
    	}
    	else
    	{
    		$returnRequest->status = 'Refunded';
    		$returnRequest->update();
    	}	

    	// create returned data

    	// create sales return
    	$array_params = [
    		'products' => $order->orderProducts,
    		'return_request_id' => $returnRequest->id,
    		'invoice_number' => $returnRequest->invoice_number,
    		'customer_id' => $returnRequest->customer_id,
    		'order_number' => $returnRequest->order_number,
    		'type' => $returnRequest->action,
    		'subtotal' => $returnRequest->subtotal,
    		'discount' => $returnRequest->discount,
    		'shipping_fee' => $returnRequest->shipping_fee,
    		'total' => $returnRequest->total
    	];

    	$this->createSalesReturn($array_params);

    	$order->remarks = "Returned products";
    	$order->update();
    	
    	return redirect()->back()->with('return-request-success', 'Refund has been created');	
    }

    public function completeReturnRequest(Request $request, ReturnRequest $returnRequest)
    {
        if ($returnRequest->status == 'Approved')
        {
            try
            {
                $customer = Customer::where('id', $returnRequest->customer_id)->first();

                $returnRequest->status = 'Completed';
                $returnRequest->update();
                
                // send email
                $url = route('customer.return_requests');

                $customer->notify(new ReturnRequestStatusNotif($returnRequest, $url));
            }
            catch(Exception $ex)
            {
                return redirect()->back()->with('return-request-error', $ex->getMessage());
            }

            return redirect()->route('return_request_details', ['returnRequest'=>$returnRequest->id])
                    ->with('return-request-success', 'Return request status has been changed to Completed.');
        }
        else
        {
            return redirect()->route('return_request_details', ['returnRequest'=>$returnRequest->id]);
        }
    }

    private function getUser()
    {
    	return Auth::guard('admin')->user();
    }

    
}
