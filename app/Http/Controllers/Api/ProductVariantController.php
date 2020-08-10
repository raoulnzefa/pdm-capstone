<?php

namespace App\Http\Controllers\Api;

use App\UserLog;
use App\Admin;
use App\Inventory;
use App\ProductVariant;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Traits\UserLogs;

class ProductVariantController extends Controller
{
    use UserLogs;

    public function perProduct(Product $product)
    {
        
        return response()->json($product->productVariants);
    }

    public function store(Request $request)
    {    
        $request->validate([
            'variant' => [
                'required',
                Rule::unique('product_variants')->where('product_sku', $request->product_sku)
                        ->where(function($query) use ($request) {
                            return $query->where('variant',$request->variant);
                        })
            ],
            'price' => 'required|regex:/^[1-9][0-9.]/|max:8',
            'quantity' => 'required|numeric|gt:critical_level|regex:/^[1-9]/',
            'critical_level' => 'required|numeric|lt:quantity|regex:/^[1-9]/'
        ]);

        // get product details
        $product = Product::where('sku', $request->product_sku)->first();

        // round price
        $price = round($request->price, 2);

        // add product variant
        $prodVariant = new ProductVariant;
        $prodVariant->number = str_random(4);
        $prodVariant->product_sku = $request->product_sku;
        $prodVariant->variant = $request->variant;
        $prodVariant->price = $price;
        
        $prodVariant->critical_level = (int)$request->critical_level;
        $prodVariant->status = $request->status;

        if ($prodVariant->save())
        {   
            $updateProdVariant = ProductVariant::where('number', $prodVariant->number)->first();
            $updateProdVariant->number = 'PV-'.str_pad($updateProdVariant->id, 4, '0', STR_PAD_LEFT);
            $updateProdVariant->update();

            // add to inventory
            $inventory = new Inventory;
            $inventory->product_sku = $request->product_sku;
            $inventory->sku = str_random(4);
            $inventory->name = $product->name.' '.$request->variant;
            $inventory->quantity = $request->quantity;
            $inventory->critical_level = $request->critical_level;
            $inventory->availability = 'In stock';
            $inventory->inventorying_id = $updateProdVariant->number;
            $inventory->inventorying_type = 'App\ProductVariant';
            $inventory->save();

            $inventSku = Inventory::where('sku',$inventory->sku)->first();
            $inventSku->sku = 'IFG'.str_pad($inventSku->id, 4, '0', STR_PAD_LEFT);
            $inventSku->update();
            
            $count_product = ProductVariant::where('product_sku', $request->product_sku)->count();

            if ($count_product > 0)
            {
                // activate product status
                $activateProduct = Product::where('sku', $request->product_sku)->first();
                $activateProduct->status = 'Active';
                $activateProduct->update();
            }

            $array_params = [
                'id' => $request->admin_id,
                'action' => 'Add new product variant. Variant ID: '.$updateProdVariant->number.', New Variant: '.$prodVariant->variant.','
            ];

            $this->createUserLog($array_params);

            $response = ['success' => true];
        }


        // send response
        return response()->json($response);
    }

    public function update(Request $request, ProductVariant $variant)
    {
        
        $request->validate([
            'variant' => [
                    'required',
                    Rule::unique('product_variants')->ignore($variant->id)
                        ->where('product_sku', $request->product_sku)
                        ->where(function($query) use ($request) {
                            return $query->where('variant',$request->variant);
                        })
            ],
            'price' => 'required|regex:/^[1-9][0-9]/|max:8',
            'critical_level' => 'required|numeric|regex:/^[1-9]/'
        ]);

        // get product data
        $product = Product::where('sku', $request->product_sku)->first();

        // round price
        $price = round($request->price, 2);

        $variant->variant = $request->variant;
        $variant->price = $price;

        $variant->critical_level = (int)$request->critical_level;
        $variant->status = $request->status;
        
        // update product variant
        if ($variant->update())
        {
            // update inventory
            $inventory = Inventory::where('inventorying_id', $variant->number)->first();
            $inventory->name = $product->name.' '.$request->variant;
            $inventory->critical_level = (int)$request->critical_level;
            $inventory->update();

             $array_params = [
                'id' => $request->admin_id,
                'action' => 'Edit product variant. Variant ID: '.$variant->number.', New Variant: '.$variant->variant.','
            ];

            $this->createUserLog($array_params);

            $response = ['success' => true];
        }

        
        return response()->json($response);
    }

    
    public function variantsInventory(Product $product)
    {
    	return response()->json($product->productVariantDetails);
    } 

    public function activeVariants($product)
    {
         $variants = ProductVariant::where('product_sku', $product)
                        ->where(function($query) {
                            $query->where('status', 'Active');
                        })
                        ->with('inventory.product')
                        ->get();

         return response()->json($variants);
    }
}
