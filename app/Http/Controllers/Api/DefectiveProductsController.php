<?php

namespace App\Http\Controllers\Api;

use App\Models\Inventory;
use App\Models\DefectiveProduct;
use App\Models\ReplacementRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DefectiveProductsController extends Controller
{
   public function getDefectiveProducts()
   {
   	$defectives = DefectiveProduct::with('inventory.product', 'replacementRequest')->get();

   	return response()->json($defectives);
   }
}
