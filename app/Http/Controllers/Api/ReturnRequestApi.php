<?php

namespace App\Http\Controllers\Api;

use App\Order;
use App\OrderProduct;
use App\ReturnRequest;
use App\ReturnProductRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ReturnRequestApi extends Controller
{
   public function saveReturnRequest(Request $request)
   {
   	try
   	{

   		$order = Order::where('number',$request->order_number)->first();
   
	   	date_default_timezone_set("Asia/Manila");

	   	$response = [];

	   	$returnRequest = new ReturnRequest;
	   	$returnRequest->customer_id = $request->customer_id;
	   	$returnRequest->invoice_number = $request->invoice_number;
	   	$returnRequest->order_number = $request->order_number;
	   	$returnRequest->reason_id = $request->reason;
	   	$returnRequest->action = 'Replacement';
	   	$returnRequest->comment = $request->comment;
	   	$returnRequest->status = 'Pending';
	   	$returnRequest->date_return_request = date('Y-m-d H:i:s');
	   	$returnRequest->subtotal = $order->subtotal;
	   	$returnRequest->shipping_fee = $order->shipping_cost;
	   	$returnRequest->total = $order->total;
	   	$returnRequest->save();

	   	$return_request_id = $returnRequest->id;

	   	$orderProduct = OrderProduct::where('id', $request->return_product_id)->first();

	   	$price = str_replace(',', '', $orderProduct->price);

	   	$returnProductRequest = new ReturnProductRequest;
	   	$returnProductRequest->return_request_id = $return_request_id;
	   	$returnProductRequest->order_product_id = $request->return_product_id;
	   	$returnProductRequest->price = $price;
	   	$returnProductRequest->quantity = (int)$request->return_qty;
	   	$returnProductRequest->ordered_qty = (int)$orderProduct->quantity;
	   	$returnProductRequest->amount = (int)$request->return_qty * $price;
	   	$returnProductRequest->save();	

	   	$url = route('return.request.submitted', ['order'=> $request->order_number]);
	   	
	   	$response = ['success' => true, 'redirect' => $url];
   	}
   	catch(Exception $ex)
   	{
   		$response = ['success' => false, 'err' => $ex->getMessage()];
   	}

   	return response()->json($response);

   }
}
