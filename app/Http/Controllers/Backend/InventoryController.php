<?php

namespace App\Http\Controllers\Backend;

use App\Models\InventoryVariant;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductWithVariant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class InventoryController extends Controller
{
    public function __construct()
    {
    	return $this->middleware('auth:admin');
    }

    public function index()
    {

    	$inventories = Inventory::with('inventoryVariant.productWithVariant')->get();
        $data = 'Inventory';

    	return view('backend.inventory.index', compact('data', 'inventories'));
    }

}
