<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderStatusController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:customer');
    }

    public function index()
    {

    	$customer_id = Auth::guard('customer')->user()->id;
    	$order_statuses = Order::where('customer_id', $customer_id)
                                ->where(function($query) {
                                    $query->where('order_status', 'For pickup')
                                        ->orWhere('order_status', 'Pending payment')
                                        ->orWhere('order_status', 'Processing')
                                        ->orWhere('order_status', 'Delivered');
                                })
                                ->orderBy('id','desc')->paginate(6);

    	return view('frontend.customer.view_order_status')
		    	->with([
		    		'data'=>'Order Status',
		    		'order_statuses'=>$order_statuses
		    	]);
    }
}
