<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\ProductWithVariant;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\UserLogs;

class InventoryController extends Controller
{
 	use UserLogs;

   public function getInventory(Request $request)
   {
        if ($request->query('search'))
        {
            // search value
            $search_val = $request->query('search');
            // get search result with pagination
            $inventory = Inventory::where(function($query) use ($search_val) {
                            $query->where('number', 'LIKE', '%'. $search_val .'%');
                        })
                        ->with('product.category','productWithVariant','productNoVariant')
                        ->paginate(10);
            // append search value

            //$inventory->appends($request->only('search'));
        }
        elseif ($request->query('filterBy'))
        {
            $filter_by = $request->query('filterBy');
            // get search result with pagination

            if ($filter_by == 'in_stock')
            {
              $inventory = Inventory::with('product.category','productWithVariant','productNoVariant')->paginate(10);
            }
            else if ($filter_by == 'critical_level')
            {
              $inventory = Inventory::whereRaw('inventory_stock <= inventory_critical_level')
                        ->with('product.category','productWithVariant','productNoVariant')
                        ->paginate(100);
            }
            else
            {
              $inventory = Inventory::whereRaw('inventory_stock = 0')
                        ->with('product.category','productWithVariant','productNoVariant')
                        ->paginate(100); 
            }

            
        }
        else
        {
            // get inventory with pagination of 5
            $inventory = Inventory::with('product.category','productWithVariant','productNoVariant')->paginate(10);
        }

    	return response()->json($inventory);
   }

   public function addStock(Request $request, Inventory $inventory)
    {
    	$request->validate([
    		'inventory_stock' => 'required|numeric'
    	]);

      // set timezone
      date_default_timezone_set("Asia/Manila");

      $old_stock = $inventory->inventory_stock;
    	// update inventory
    	$inventory->inventory_stock += (int)$request->inventory_stock;
    	$inventory->update();

     	$array_params = [
         'id' => $request->admin_id,
         'action' => 'Added new stock(s). Inventory no.: '.$inventory->number.'. New added stock: '.$request->inventory_stock.'. Old stock: '.$old_stock.' Total stocks: '.$inventory->inventory_stock
      ];

      $this->createUserLog($array_params);

    	return response()->json(['success' => true]);

    }

    public function inventoryAlert()
    {
       try
      {
         $inventoryAlert = Inventory::whereRaw('inventory_stock <= inventory_critical_level')
            ->orWhereRaw('inventory_stock = 0')
            ->count();

         $response = ['count'=> $inventoryAlert];
      }
      catch(Exception $e)
      {
         $response = ['err' =>$e->getMessage()];
      }

      return response()->json($response);
    }  
}
