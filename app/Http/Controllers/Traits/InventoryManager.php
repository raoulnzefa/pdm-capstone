<?php

namespace App\Http\Controllers\Traits;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderProduct;


trait InventoryManager
{
	public function updateInventory($order_number)
	{
		//Deduct stocks
     	$orderProducts = OrderProduct::where('order_number', $order_number)->get();
    
     	foreach ($orderProducts as $product) {

         $inventDeduct = Inventory::where('number','=',$product->inventory_number)->first();

         if ($inventDeduct->inventory_stock > 0)
         {
             $inventDeduct->inventory_stock -= $product->quantity;        

             $inventDeduct->update();
        }

    	}
	}

	public function restockOrder($order_number)
	{
        $orderProducts = OrderProduct::where('order_number', $order_number)->get();
    
        foreach ($orderProducts as $product) {

            $inventRestock = Inventory::where('number','=',$product->inventory_number)->first();
            $inventRestock->inventory_stock += $product->quantity;

            $inventRestock->update();

        }
	}

    public function restockItem($inventory_number,$quantity)
    {

        $inventory = Inventory::where('number',$inventory_number)->first();
        $inventory->inventory_stock += $quantity;
        $inventory->update();
    }

}