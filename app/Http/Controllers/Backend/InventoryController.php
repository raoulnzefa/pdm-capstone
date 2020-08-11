<?php

namespace App\Http\Controllers\Backend;

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

    	$inventories = DB::table('inventories')
    						->leftJoin('product_with_variants', 'inventories.number', '=','product_with_variants.inventory_number')
    						->join('products', 'inventories.product_number', '=', 'products.number')
    						->select('inventories.*', 'product_with_variants.variant_value','products.product_name')
    						->get();
      $data = 'Inventory';

    	return view('backend.inventory.index', compact('data', 'inventories'));
    }

}
