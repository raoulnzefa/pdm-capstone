<?php

namespace App\Http\Controllers\Api;

use App\UserLog;
use App\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\UserLogs;

class InventoryController extends Controller
{
    use UserLogs;

    public function list(Request $request)
    {
        if ($request->query('search'))
        {
            // search value
            $search_val = $request->query('search');
            // get search result with pagination
            $inventory = Inventory::where(function($query) use ($search_val) {
                            $query->where('name', 'LIKE', '%'. $search_val .'%')
                                ->orWhere('sku', $search_val);
                        })
                        ->with('product','inventorying')
                        ->paginate(10);
            // append search value

            //$inventory->appends($request->only('search'));
        }
        else
        {
            // get inventory with pagination of 5
            $inventory = Inventory::with('product', 'inventorying')->paginate(10);
        }

    	return response()->json($inventory);
    }

    public function addStock(Request $request, Inventory $inventory)
    {
    	$request->validate([
    		'quantity' => 'required|numeric'
    	]);

        // set timezone
        date_default_timezone_set("Asia/Manila");

        $old_stock = $inventory->quantity;
    	// update inventory
    	$inventory->quantity += $request->quantity;
        $inventory->availability = 'In stock';
    	$inventory->update();

        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Add stocks. SKU: '.$inventory->sku.', Old stock: '.$old_stock.' New stock: '.$inventory->quantity,
        ];

        $this->createUserLog($array_params);

    	return response()->json(['success' => true]);

    }

    public function totalStocks()
    {
    	$total_stocks = Inventory::sum('quantity');
    	return response()->json($total_stocks);
    }

    public function inventoryReport()
    {
        $inventory = Inventory::all();
        $stocks = Inventory::sum('quantity');
        // set timezone
        date_default_timezone_set("Asia/Manila");
        $date_now = date(' F d, Y h:i A');

        return response()->json(['inventory'=>$inventory, 'stocks'=> $stocks, 'date_now'=> $date_now]);
    }

    public function criticalLevels()
    {
        $critical_levels = Inventory::whereColumn('quantity', '<=', 'critical_level')->count();
        return response()->json($critical_levels);
    }

    public function outOfStock()
    {
        $out_of_stock = Inventory::where('quantity', '=', 0)->count();
        return response()->json($out_of_stock);   
    }

    public function criticalLevelReport()
    {
        // set timezone
        date_default_timezone_set("Asia/Manila");
        $date_now = date(' F d, Y h:i A');

        $critical_level_report = Inventory::whereColumn('quantity', '<=', 'critical_level')->get();
        return response()->json(['critical_levels'=>$critical_level_report, 'date_now'=>$date_now]);
    }

    public function outOfStockReport()
    {
        // set timezone
        date_default_timezone_set("Asia/Manila");
        $date_now = date(' F d, Y h:i A');

        $out_of_stock_report = Inventory::where('quantity', '=', 0)->get();
        
        return response()->json(['out_of_stocks'=>$out_of_stock_report, 'date_now'=>$date_now]);
    }

    public function adjustQuantity(Request $request, Inventory $inventory)
    {
        $request->validate([
            'quantity' => 'required'
        ]);

        $old_stock = $inventory->quantity;

        $inventory->quantity = $request->quantity;
        $inventory->update();

        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Adjust stocks. SKU: '.$inventory->sku.', Old stock: '.$old_stock.' Adjusted stock: '.$inventory->quantity,
        ];

        $this->createUserLog($array_params);

        return response()->json(['success' => true]);
    }
}
