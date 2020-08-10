<?php

namespace App\Http\Controllers\Api;

use App\Inventory;
use App\Cart;
use App\Customer;
use App\Product;
use App\ProductNoVariant;
use App\ProductVariant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function addCartPage(Request $request)
    {
        //return response()->json($request->all());
        // get product data
        $product = Product::where('sku', $request->product_sku)->first();

        if ($product->has_variant == 'No')
        {
            $carting_id = $request->no_variant_number;
            $productChild = ProductNoVariant::where('number',$request->no_variant_number)->first();
            $carting_type = 'App\ProductNoVariant';
        }
        else
        {
            $carting_id = $request->variant_number;
            $productChild = ProductVariant::where('number',$request->variant_number)->first();
            $carting_type = 'App\ProductVariant';
        }

        // check if customer email and product exists in cart table
        $product_exists = Cart::where(
                            [
                                'customer_id' => $request->customer_id,
                                'carting_id' => $carting_id
                            ])
                            ->exists();

        $inventory = Inventory::where('inventorying_id', $carting_id)->first();

        // check if exists in cart
        if ($product_exists)
        {
            // update
            // get cart details
            $cart = Cart::where('carting_id', $carting_id)->first();
            
            $cart_qty = $cart->quantity + $request->quantity;

            //return response()->json($cart_qty);
            if ($cart_qty > $inventory->quantity)
            {
                $response = ['invalid_qty' => true];

                return response()->json($response);
            }
            else
            {
                $cart->price = str_replace(',', '', $productChild->price);
                
                $cart->quantity += (int)$request->quantity;

                $cart->total = round($cart->quantity * str_replace(',', '', $cart->price), 2);
                
                $cart->update();

                $response = ['success'=>true];

                return response()->json($response);
            }
        }
        else
        {

            if ($request->quantity > $inventory->quantity)
            {
                return response()->json(['invalid_qty'=>true]);
            }
            else
            {
                // add new
                $cart = new Cart;
                $cart->customer_id = $request->customer_id;
                $cart->product_sku = $request->product_sku;

                $cart->price = str_replace(',', '', $productChild->price);
                
                $cart->quantity = (int)$request->quantity;

                $cart->total = round($cart->quantity * str_replace(',', '', $cart->price), 2);
                $cart->carting_id = $carting_id;
                $cart->carting_type = $carting_type;
                $cart->save();

                $response = ['success'=>true]; 

                return response()->json($response);
            }                
        }

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
            $cart_details = Cart::where('customer_id', $customer)->with('carting.inventory','product')->get();         
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
        $product = Product::where('sku', $cart->product_sku)->first();
        //$product_price = str_replace(',', '', $product->price);

        $cart->quantity = $request->quantity;
        $cart->total = $request->quantity * str_replace(',', '', $cart->price);
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
