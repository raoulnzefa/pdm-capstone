<?php

namespace App\Http\Controllers\Backend;

use App\InventoryVariant;
use App\Inventory;
use App\Product;
use App\ProductWithVariant;
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
