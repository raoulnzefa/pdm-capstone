<?php

namespace App\Http\Controllers\Traits;

use App\Inventory;
use App\Product;
use App\Order;
use App\OrderProduct;


trait InventoryManager
{
	public function updateInventory($order_number)
	{
		//Deduct stocks
     	$orderProducts = OrderProduct::where('order_number', $order_number)->get();
    
     	foreach ($orderProducts as $product) {

         $inventDeduct = Inventory::where('sku','=',$product->inventory_sku)->first();
         $inventDeduct->quantity -= $product->quantity;

         // check if product is 0 stock
         if ($inventDeduct->quantity == 0)
         {

            $inventDeduct->availability = 'Out of stock';
         }

         $inventDeduct->update();

    	}
	}

	public function restockOrder($order_number)
	{
        $orderProducts = OrderProduct::where('order_number', $order_number)->get();
    
        foreach ($orderProducts as $product) {

            $inventRestock = Inventory::where('sku','=',$product->inventory_sku)->first();
            $inventRestock->quantity += $product->quantity;

            if ($inventRestock->quantity >= 1)
            {

                $inventRestock->availability = 'In stock';
            }

            $inventRestock->update();

        }
	}

}