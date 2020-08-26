<?php

namespace App\Http\Controllers\Api;

use App\Models\Inventory;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductNoVariant;
use App\Models\ProductWithVariant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function addToCartVariant(Request $request)
    {
        $request->validate([
            'variant' => 'required',
            'quantity' => 'required'
        ]);

        date_default_timezone_set("Asia/Manila");

        $exists = Cart::where([
                'inventory_number' => $request->variant,
                'customer_id' => $request->customer_id
            ])->exists();

        $response = [];

        $variant = ProductWithVariant::where('inventory_number',$request->variant)->first();

        if (!$exists)
        {
            $cart = new Cart();
            $cart->customer_id = (int)$request->customer_id;
            $cart->inventory_number = $request->variant;
            $cart->product_name = $variant->product->product_name.' '.$variant->variant_value;
            $cart->price = $variant->variant_price;
            $cart->quantity = (int)$request->quantity;
            $cart->in_stock = (int)$variant->inventory->inventory_stock;
            $cart->total = ($cart->quantity * $cart->price);
            $cart->save();
            
            $response = ['success' => true];            
        }
        else
        {
            $cart = Cart::where([
                'inventory_number' => $request->variant,
                'customer_id' => $request->customer_id
            ])->first();

            $cart->quantity += (int)$request->quantity;
            $cart->total = ($cart->quantity * $cart->price);
            $cart->update();

            $response = ['success' => true];    
        }

        
        return response()->json($response);
    }

    public function addToCartNoVariant(Request $request)
    {
        $request->validate([
            'product' => 'required',
            'quantity' => 'required'
        ]);

        date_default_timezone_set("Asia/Manila");

        $exists = Cart::where([
                'inventory_number' => $request->product,
                'customer_id' => $request->customer_id
            ])->exists();

        $response = [];

        $no_variant = ProductNoVariant::where('inventory_number', $request->product)->first();

        if (!$exists)
        {
            $cart = new Cart();
            $cart->customer_id = (int)$request->customer_id;
            $cart->inventory_number = $request->product;
            $cart->product_name = $no_variant->product->product_name;
            $cart->price = (float)$no_variant->price;
            $cart->quantity = (int)$request->quantity;
            $cart->in_stock = (int)$no_variant->inventory->inventory_stock;
            $cart->total = ($cart->quantity * $cart->price);
            $cart->save();
            
            $response = ['success' => true];        
        }
        else
        {
            $cart = Cart::where([
                'inventory_number' => $request->product,
                'customer_id' => $request->customer_id
            ])->first();

            $cart->quantity += (int)$request->quantity;
            $cart->total = ($cart->quantity * $cart->price);
            $cart->update();

            $response = ['success' => true];    
        }

        return response()->json($response);
    }

    public function cartQuantity($customer)
    {
        $has_product = Cart::where('customer_id', $customer)->exists();
        if ($has_product)
        {
            $quantity = Cart::where('customer_id', $customer)->sum('quantity');   
        }
        else
        {
            $quantity = 0;
        }
        

        return response()->json($quantity);
    }

    public function customerCart($customer)
    {
        $has_product = Cart::where('customer_id',$customer)->exists();

        if ($has_product)
        {
            $cart_details = Cart::where('customer_id', $customer)->with('inventory.product','inventory.productWithVariant')->get();         
            $response = $cart_details;
        }
        else
        {
            $response = [];
        }

        return response()->json($response);
    }

    public function updateQuantity(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required'
        ]);
        
        $cart->quantity = (int)$request->quantity;
        $cart->total = (int)$request->quantity * (float)$cart->price;
        $cart->update();

        return response()->json(array('success'=>true));

    }

    public function removeProduct(Cart $cart)
    {
        $cart->delete();

        return response()->json(array('deleted'=>true));
    }

    public function cartTotalDetails($customer)
    {
        $exists = Cart::where('customer_id', $customer)->exists();

        if ($exists)
        {
            $subtotal = Cart::where('customer_id', $customer)->sum('total');
            $cart_qty = Cart::where('customer_id', $customer)->sum('quantity');
            

            return array(
                'product_qty' => $cart_qty,
                'subtotal'=>number_format($subtotal, 2),
                'total'=>number_format($subtotal, 2)
            );
        }
    }

    public function updateCart(Request $request)
    {
        foreach ($request->cart_update as $newQty) {
            $cart = Cart::where('customer_id',$newQty['customer_id'])->where('product_sku', $newQty['product_sku'])->first();

            $prod = Product::where('sku', $newQty['product_sku'])->first();

            $inventory = Inventory::where('inventorying_id', $newQty['carting_id'])->first();

            $customer_id = $newQty['customer_id'];

           
            $cart->quantity = $newQty['quantity'];
            $cart->total = str_replace(',', '', $cart->price) * $newQty['quantity'];
            $cart->update();

                
        }

        $cart_updated = Cart::where('customer_id', $customer_id)->with('carting.inventory','product')->get();
        $cart_total = Cart::where('customer_id', $customer_id)->sum('total');
        $cart_qty = Cart::where('customer_id', $customer_id)->sum('quantity');

        return response()->json([
            'success' => true,
            'updated_cart' => $cart_updated,
            'product_qty' => $cart_qty,
            'subtotal' => number_format($cart_total,2),
            'total' => number_format($cart_total,2)
        ]);
    }

}
