<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\ProductWithVariant;
use App\Models\Inventory;
use App\Models\InventoryVariant;
use App\Http\Controllers\Traits\UserLogs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class ProductWithVariantController extends Controller
{
   use UserLogs;

   public function list()
   {
   	$productWithVariants = ProductWithVariant::with('product')->get();

   	return response()->json($productWithVariants);
   }

   public function updateVariant(Request $request, ProductWithVariant $productVariant)
   {
   	$request->validate([
         'variant_value' => [
              	'required',
              	Rule::unique('product_with_variants')->ignore($productVariant->id)
               	->where('product_number', $productVariant->product_number)
                  ->where(function($query) use ($request) {
                     return $query->where('variant_value',$request->variant_value);
                  })
         ],
         'variant_price' => 'required|regex:/^[1-9][0-9]/|max:8'
      ]);

   	date_default_timezone_set("Asia/Manila");

   	$productVariant->variant_value = $request->variant_value;
   	$productVariant->variant_price = (float)$request->variant_price;
   	$productVariant->variant_status = (int)$request->variant_status;
   	$productVariant->update();

   	$array_params = [
            'id' => $request->admin_id,
            'action' => 'Updated product variant. Variant ID: '.$productVariant->id
      ];

      $this->createUserLog($array_params);

      $response = array('success'=>true);
        
      return response()->json($response);
   }

   public function createVariant(Request $request)
   {
      $request->validate([
         'variant_value' => [
               'required',
               Rule::unique('product_with_variants')
                  ->where('product_number', $request->product_number)
                  ->where(function($query) use ($request) {
                     return $query->where('variant_value',$request->variant_value);
                  })
         ],
         'variant_price' => 'required|regex:/^[1-9][0-9]/|max:8',
         'inventory_stock' => 'required|numeric|gte:inventory_critical_level|regex:/^[1-9]/',
         'inventory_critical_level' => 'required|numeric|lte:inventory_stock|regex:/^[1-9]/'
      ]);

      date_default_timezone_set("Asia/Manila");

      $inventory = new Inventory();
      $inventory->number = str_random(5);
      $inventory->product_number = $request->product_number;
      $inventory->inventory_stock = (int)$request->inventory_stock;
      $inventory->inventory_critical_level = (int)$request->inventory_crit_level;
      $inventory->save();

      $inventoryUpdate = Inventory::where('number', $inventory->number)->first();
      $inventoryUpdate->number = str_pad($inventoryUpdate->id, 5, '0', STR_PAD_LEFT);
      $inventoryUpdate->update();

      $productVariant = new ProductWithVariant();
      $productVariant->product_number = $request->product_number;
      $productVariant->variant_value = $request->variant_value;
      $productVariant->variant_price = (float)$request->variant_price;
      $productVariant->variant_status = (int)$request->variant_status;
      $productVariant->save();

      $inventoryVariant = new InventoryVariant();
      $inventoryVariant->inventory_number = $inventoryUpdate->number;
      $inventoryVariant->variant_id = (int)$productVariant->id;
      $inventoryVariant->save();

      $array_params = [
            'id' => $request->admin_id,
            'action' => 'Added product variant. Variant ID: '.$productVariant->id
      ];

      $this->createUserLog($array_params);

      $response = array('success'=>true);
        
      return response()->json($response);
   }
}
