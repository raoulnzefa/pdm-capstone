<?php

namespace App\Http\Controllers\Traits;

use App\Order;
use App\OrderProduct;
use App\Inventory;
use App\Product;

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
         $order->order_delivery_method = $array_params['order_delivery_method'];
         $order->order_payment_status = $array_params['order_payment_status'];
         $order->order_payment_method = $array_params['order_payment_method'];
         $order->order_quantity = $array_params['order_quantity'];
         $order->order_subtotal = $array_params['order_subtotal'];
         $order->order_total = $array_params['order_total'];
         $order->order_payment_date = $array_params['order_payment_date'];
         $order->order_paypal_url = $array_params['order_paypal_url'];
         $order->order_created = date("Y-m-d H:i:s");

         $order->save();

         $updateOrder = Order::where('number', $order->number)->first();
         $updateOrder->number = str_pad($updateOrder->id, 6, '0', STR_PAD_LEFT);
         $updateOrder->update();

         //Save ordered products
         foreach ($array_params['cart_products'] as $cart) {

             $invent = Inventory::where(['product_sku'=>$cart->product_sku, 'inventorying_id'=>$cart->carting_id])->first();

             $product = Product::where('sku', $cart->product_sku)->first();

             $orderProduct = new OrderProduct;
             $orderProduct->order_number = $updateOrder->number;
             $orderProduct->inventory_sku = $invent->sku;
             $orderProduct->product_name = $invent->name;
             $orderProduct->price = str_replace(',', '', $cart->price);
             $orderProduct->quantity = $cart->quantity;
             $orderProduct->total = str_replace(',', '', $cart->total);
             $orderProduct->ordering_id = $cart->carting_id;
             $orderProduct->ordering_type = $cart->carting_type;
             $orderProduct->status = 'Reserved';
             $orderProduct->save();

         }
         // update inventory
         return $updateOrder->number;
       	
	}
}