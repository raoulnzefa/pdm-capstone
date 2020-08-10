<?php

namespace App\Http\Controllers\Frontend;

use App\Notifications\RefundNotification;
use App\Invoice;
use App\CancelOrderRequest;
use App\Order;
use App\Reason;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CancellationController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:customer');
    }

    public function cancelRequest(Order $order)
    {
        $reasons = Reason::all();
        $order->customer;

        return view('frontend.customer.cancel_order', compact('order','reasons'));
    }

    public function submitted()
    {
        return view('frontend.order.cancellation_submitted');
    }

    public function store(Request $request)
    {
    	$request->validate([
    		'reason' => 'required',
            'comment' => 'required'
    	]);

        $total = Order::where('number', $request->order_number)->first()->total;
    	// set Manila timezone
    	date_default_timezone_set("Asia/Manila");
    	// save cancellation details
    	$cancelRequest = new CancelOrderRequest;
    	$cancelRequest->customer_id = $request->customer_id;
        $cancelRequest->order_number = $request->order_number;
        $cancelRequest->reason_id = $request->reason;
        $cancelRequest->comment = $request->comment;
        $cancelRequest->status = "Pending";
        $cancelRequest->date_request = date('Y-m-d H:i:s');
        $cancelRequest->total = str_replace(',', '', $total);
        $cancelRequest->save();

    	return redirect()->route('cancel.request.submitted')->with(['order_number'=> $request->order_number]);

    }

    public function details(CancelOrderRequest $cancelRequest) {
        // update cancellation status_update view to 0
        $cancelRequest->status_update = 0;
        $cancelRequest->update();
        
        return view('frontend.customer.cancel_request_details')->with(['data'=>'Cancellation Request Details', 'cancelRequest'=> $cancelRequest]);
    }

    
}
