<?php

namespace App\Http\Controllers\Traits;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Inventory;
use App\Models\Product;

trait OrderTraits
{
	public function createOrder($array_params)
	{    
		 // set timezone
        date_default_timezone_set("Asia/Manila");

         // save order
         $order = new Order;
         $order->number = str_random(5);
         $order->customer_id = $array_params['customer_id'];
         $order->order_status = $array_params['order_status'];
         $order->order_shipping_method = $array_params['order_shipping_method'];
         $order->order_payment_status = $array_params['order_payment_status'];
         $order->order_payment_method = $array_params['order_payment_method'];
         $order->order_quantity = $array_params['order_quantity'];
         $order->order_shipping_fee = $array_params['order_shipping_fee'];
         $order->order_subtotal = $array_params['order_subtotal'];
         $order->order_discount = $array_params['order_discount'];
         $order->order_total = $array_params['order_total'];
         $order->order_shipping_discount = $array_params['order_shipping_discount'];
         $order->order_payment_date = $array_params['order_payment_date'];
         $order->order_due_payment = $array_params['order_due_payment'];
         $order->order_follow_up_email = $array_params['order_follow_up_email'];
         $order->order_paypal_url = $array_params['order_paypal_url'];
         $order->order_for_shipping = $array_params['order_for_shipping'];
         $order->order_for_pickup = $array_params['order_for_pickup'];
         $order->order_reserved_days = $array_params['order_reserved_days'];
         $order->order_processing_days = $array_params['order_processing_days'];
         $order->order_due_payment_days = $array_params['order_due_payment_days'];
         $order->order_created = date("Y-m-d H:i:s");

         $order->save();

         $updateOrder = Order::where('number', $order->number)->first();
         $updateOrder->number = str_pad($updateOrder->id, 6, '0', STR_PAD_LEFT);
         $updateOrder->update();

         //Save ordered products
         foreach ($array_params['cart_products'] as $cart) {

             // $invent = Inventory::where(['product_number'=>$cart->product_sku, 'inventorying_id'=>$cart->carting_id])->first();

             $invent = Inventory::where('number',$cart->inventory_number)->first();

             $productName = (!is_null($invent->productWithVariant)) ? $invent->product->product_name.' '.$invent->productWithVariant->variant_value : $invent->product->product_name;

             $orderProduct = new OrderProduct;
             $orderProduct->order_number = $updateOrder->number;
             $orderProduct->inventory_number = $invent->number;
             $orderProduct->product_name = $productName;
             $orderProduct->price = str_replace(',', '', $cart->price);
             $orderProduct->quantity = $cart->quantity;
             $orderProduct->total = str_replace(',', '', $cart->total);
             $orderProduct->status = 'Reserved';
             $orderProduct->save();

         }
         // update inventory
         return $updateOrder->number;
       	
	}
}