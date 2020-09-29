<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Inventory;
use App\Http\Controllers\Traits\InventoryManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class OrdersController extends Controller
{
    use InventoryManager;

    public function __construct()
    {
    	$this->middleware('auth:customer');
    }

    public function index()
    {
        $customer_id = Auth::guard('customer')->user()->id;

        // check for overdue orders
        $overdue = Order::where('order_status','!=','Overdue')->where('customer_id',$customer_id)->where(function($query) {
            $query->whereRaw('order_for_pickup < CURRENT_DATE')
            ->orWhereRaw('order_due_payment < CURRENT_DATE');
        })->get();

        foreach ($overdue as $item)
        {
            $this->restockOrder($item->number);

            $order = Order::where('number', $item->number)->first();
            $order->order_status = 'Overdue';
            $order->order_restocked = 1;
            $order->viewed = 0;
            $order->order_remarks = 'Restocked';
            $order->update();
        }

   
    	// $orders = Order::where('customer_id','=', $customer_id)->where(function($query) {
     //        $query->where('order_status','=','Completed');
     //    })->paginate(6);

        $orders = Order::where('customer_id','=', $customer_id)->paginate(10);

    	return view('frontend.customer.orders')
    			->with([
    				'data'=>'Orders',
    				'orders'=>$orders
    			]);
    }
}
