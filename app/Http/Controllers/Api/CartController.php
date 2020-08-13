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
    public function addProductNoVariant(Request $request)
    {

    }

    public function addProductWithVariant(Request $request)
    {

    }

    public function addCartPage(Request $request)
    {
        

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
