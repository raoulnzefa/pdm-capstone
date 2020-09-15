<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ReplacementRequest;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class ReplacementRequestController extends Controller
{
   public function __construct()
   {
   	$this->middleware('auth:customer');
   }

   public function index()
   {
      $data = 'Replacements';
   	$replacements = ReplacementRequest::with('inventory.product')->get();

   	return view('frontend.replacement.index', compact('data','replacements'));
   }

   public function requestReplacement($orderNum)
   {
      $customer = Auth::guard('customer')->user()->id;
      $request_exists = ReplacementRequest::where([
            'customer_id' => $customer,
            'order_number' => $orderNum
         ])->exists();

      if (!$request_exists)
      {
         $data = 'Request Replacement';
         $order = Order::where('number',$orderNum)->with('orderProducts.inventory.product')->first();

         return view('frontend.replacement.request_replacement', compact('data','order'));
      }
      else
      {
         return redirect()->route('customer.replacements');
      }
   }

   public function store(Request $request)
   {
      $request->validate([
         'reason' => 'required',
         'quantity' => 'required'
      ]);

      date_default_timezone_set("Asia/Manila");

      $request_product = OrderProduct::where('id',(int)$request->order_product_id)->first();

      $request_exists = ReplacementRequest::where([
            'customer_id' => (int)$request->customer,
            'order_number' => $request->order_number
         ])->exists();

      if (!$request_exists)
      {
         $product = Inventory::where('number',$request_product->inventory_number)->first();
         $replacement = new ReplacementRequest();
         $replacement->customer_id = (int)$request->customer; 
         $replacement->order_number = $request->order_number;
         $replacement->inventory_number = $request_product->inventory_number;
         $replacement->product_number = $product->product_number;
         $replacement->product_name = $request_product->product_name;
         $replacement->quantity = (int)$request->quantity;
         $replacement->reason = $request->reason;
         $replacement->status = 'Pending';
         $replacement->request_created = date('Y-m-d H:i:s');
         $replacement->status_update = 1;
         $replacement->save();

         return redirect()->route('replacement.request.submitted')->with([
            'has_request'=>'YES',
            'order_number' => $request->order_number
         ]);
      }
      else
      {
         return redirect()->route('customer.replacements');
      }

   }

   public function submitted()
   {
      if (session()->has('has_request'))
      {
         $data = 'Request Submitted';
         $order_number = session()->get('order_number');
         return view('frontend.replacement.submitted', compact('order_number','data'));
      }

      return redirect()->route('customer.replacements');
   }
}
