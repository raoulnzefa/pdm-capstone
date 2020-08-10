<?php

namespace App\Http\Controllers\Backend;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    public function __construct()
    {
    	return $this->middleware('auth:admin');
    }

    public function index()
    {
    	return view('backend.inventory.index');
    }

    public function criticalLevel()
    {
        return view('backend.inventory.critical_level');
    }

    public function outOfStock()
    {
        return view('backend.inventory.out_of_stock');
    }

    public function variants(Product $product)
    {
    	return view('backend.inventory.variants', compact('product'));
    }
}
