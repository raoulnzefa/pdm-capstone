<?php

namespace App\Http\Controllers\Frontend;

use App\Models\BankDepositSlip;
use Auth;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadDepositSlipController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:customer');
    }

    public function index(Order $order)
    {
    	return view('frontend.upload_deposit_slip.index')->with(['order'=>$order, 'data'=>'Upload Deposit Slip']);
    }

    public function store(Request $request)
    {
    	$validated = $request->validate([
    		'deposit_slip' => 'required'
    	]);

    	// set image name
        $imageName = time().str_replace(' ', '-', $request->file('deposit_slip')->getClientOriginalName());

        // save image into storage
        $request->deposit_slip->storeAs('/public/deposit_slip/', $imageName);

        $order = Order::where('number', $request->order_number)->with('bankDepositPayment')->first();

        date_default_timezone_set("Asia/Manila");

        $depositSlip = new BankDepositSlip;
        $depositSlip->order_number = $request->order_number;
        $depositSlip->customer_id = $order->customer_id;
        $depositSlip->bank_deposit_payment_id = $order->bankDepositPayment->id;
        $depositSlip->image = $imageName;
        $depositSlip->save();

        return redirect()->route('uploaded_msg', ['order'=>$order->number]);

    }

    public function uploadedMessage(Order $order)
    {
    	return view('frontend.upload_deposit_slip.uploaded_msg')
    			->with([
    				'order'=>$order,
    				'data'=>'Deposit Slip Uploaded'
    			]);
    }

}
