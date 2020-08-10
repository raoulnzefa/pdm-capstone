<?php

namespace App\Http\Controllers\Frontend;

use App\CancelProduct;
use App\Notifications\OrderConfirmation;
use App\Order;
use App\OrderProduct;
use App\Invoice;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;

class HomePageController extends Controller
{
    public function index()
    {   
        
        // date_default_timezone_set("Asia/Manila");

        // $now = Carbon::now();

        // $start_of_week = $now->startOfWeek()->format('Y-m-d');
        // $end_of_week = $now->endOfWeek()->format('Y-m-d');
        // return $start_of_week.' '.$end_of_week;
        // $cancelProducts = CancelProduct::where('cancellation_number', '000001')->with('orderProduct')->get();

        // $subtotal = 0;
        // $shipping_fee = 0;
        // foreach ($cancelProducts as $item)
        // {
        //     $subtotal += str_replace(',', '', $item->orderProduct->price) * $item->quantity;
        //     $shipping_fee += $item->orderProduct->shipping_rate * $item->quantity;
        // }
        // return $shipping_fee;
        // $total = 14200 / 12;
        // return round($total * .10, 2);
        //return Auth::guard('customer')->user();
        //return str_pad(10, 4, '0', STR_PAD_LEFT);
        // date_default_timezone_set("Asia/Manila");
    	// return date('h:i:s');
        // $today = date('Y-m-d');

        // return $orders_today = Order::whereDate('created_at', $today)->count();
        // $d = 3;
        // $date = strftime("%Y-%m-%d", strtotime("+$d weekday"));

        // return date($date.' H:i:s');
        // $curdate = date('Y-m-d');
        // $mydate=getdate(strtotime($curdate));
        
        // switch($mydate['wday']){
        //     case 0: // sun
        //     case 1: // mon
        //         $days = 4;
        //         break;
        //     case 2:    
        //     case 3:
        //     case 4:
        //     case 5:
        //         $days = 6;
        //         break;
        //     case 6: // sat
        //         $days = 5;
        //         break;
        // }
        // date('Y-m-d', strtotime("$curdate +$days days"));

        //delivery working days....
        // $d = 2;
        // $date = strftime("%Y-%m-%d", strtotime("+$d weekday"));   

        // return $date;     
    	   //return date('M. d, Y');
        // $down_percent = 50 / 100;
        // return $down_amount = str_replace(',', '', '3,000');

     //    $user = Auth::guard('customer')->user();
    	// $order = Order::where('customer_email', $user->email)->first();
     //    $user->notify(new OrderConfirmation($order));
        
        //$user->notify(new OrderConfirmation($order));
    	// echo date('m/d/Y', strtotime("+30 days"));	
        //return date('ymdh').substr(str_shuffle("0123456789"), 0, 3);
    	$featured_products = Product::where(['featured'=> 'Yes', 'status'=> 'Active'])->with('productNoVariant','productVariants')->get();
        $data = 'Home';
    	return view('frontend.home')->with(['featured_products' => $featured_products, 'data'=>$data]);

        // $id = 145;

        // return str_pad($id, 8,'0', STR_PAD_LEFT);
  		//date_default_timezone_set("Asia/Manila");

		// return date("Y-m-d H:i:s");
    }
}
