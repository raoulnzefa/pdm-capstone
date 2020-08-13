<?php

namespace App\Http\Controllers\Frontend;

use App\Models\ReturnRequest;
use App\Models\ReturnProductRequest;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Reason;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Auth;

class ReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function store(Request $request)
    {
        $return_qty = 0;

        $return_subtotal = 0;

        $return_values = [];

        foreach ($request->order_prod_id as $id => $data)
        {
            $orderProd = OrderProduct::where('id', $request->order_prod_id[$id])->first();

            $return_values[] = [
                'order_prod_id' => $request->order_prod_id[$id],
                'price'=> str_replace(',', '', $orderProd->price),
                'return_qty' => $request->return_qty[$id],
                'ordered_qty' => $request->ordered_qty[$id]
            ];

            $return_qty += (int)$request->return_qty[$id];
            $return_subtotal += (float)str_replace(',', '', $orderProd->price);
        }

        if ($return_qty > 0)
        {
            //dd($return_values);
        	date_default_timezone_set("Asia/Manila");

        	$orderProducts = OrderProduct::where('order_number',$request->order_number)->get();

            $order = Order::where('number', $request->order_number)
                    ->with('invoice')->first();

            $subtotal = str_replace(',', '', $order->subtotal);

            $discount = str_replace(',', '', $order->discount);

            $total = str_replace(',', '', $order->total);

            $discount_per_product = $discount / $order->quantity;
            $return_discount = $return_qty * $discount_per_product;

            $return_total = $return_subtotal - $return_discount;

        	// save return data;
        	$return = new ReturnRequest;
            $return->customer_id = Auth::guard('customer')->user()->id;
        	$return->order_number = $request->order_number;
            $return->invoice_number = $order->invoice->number;
        	$return->reason_id = $request->reason;
        	$return->action = $request->action;
        	$return->comment = ucfirst(strtolower($request->comment));
        	$return->status = 'Pending';
        	$return->date_return_request = date('Y-m-d H:i:s');
            $return->subtotal = $return_subtotal;
            $return->discount = $return_discount;
            $return->total = $return_total;
        	$return->save();

        	foreach ($return_values as $data)
            {
                $orderProd = OrderProduct::where('id', $data['order_prod_id'])->first();

                $returnProd = new ReturnProductRequest;
                $returnProd->return_request_id = $return->id;
                $returnProd->order_product_id = $data['order_prod_id'];
                $returnProd->quantity = $data['return_qty'];
                $returnProd->price = str_replace(',', '', $orderProd->price);
                $returnProd->ordered_qty = $data['ordered_qty'];
                $returnProd->amount = $data['return_qty'] * str_replace(',', '', $orderProd->price);
                $returnProd->save();

            }
       
        	return redirect()->route('return.request.submitted', ['order'=>$request->order_number])
                    ->with([
                        'return_request'=>'Your return request has been submitted successfully.',
                        'order_number'=>$request->order_number]);
        }
        else
        {
            return redirect()->back()->with('qty_error','Invalid quantity. Please try again.')->withInput();
        }
    }

    public function submitted($order)
    {
    	// if (Session::has('return_request'))
    	// {
    	// 	$redirect = view('frontend.order.return_request_submitted');
    	// }
    	// else
    	// {
    	// 	$redirect = redirect()->route('customer.return_requests');;
    	// }
    	// return $redirect;

        return view('frontend.order.return_request_submitted', compact('order'));
    }

    public function details(ReturnRequest $returnRequest)
    {
        return view('frontend.order.return_request_details')
                    ->with([
                        'returnRequest'=>$returnRequest,
                        'data'=>'Return Request Details'
                    ]);
    }
}
