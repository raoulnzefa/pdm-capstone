<?php

namespace App\Http\Controllers\Frontend;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
class OrderHistoryController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:customer');
    }

    public function index()
    {

        $customer_id = Auth::guard('customer')->user()->id;
   
    	$orders = Order::where('customer_id','=', $customer_id)->where(function($query) {
            $query->where('order_status','=','Completed');
        })->paginate(6);

    	return view('frontend.customer.order_history')
    			->with([
    				'data'=>'Order History',
    				'orders'=>$orders
    			]);
    }
}
