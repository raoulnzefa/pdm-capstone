<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ViewProductController extends Controller
{
    public function __construct()
	{
		return $this->middleware('auth:customer');
	}

	public function viewProduct($product)
    {   
    		$customer_id = Auth::guard('customer')->user()->id;

        	$prod = Product::where('url', $product)->with(['productNoVariant.inventory','inventory'])->first();
        	$cartNumber = Cart::where('customer_id', $customer_id)->count();
    	
    		$cart = Cart::where('customer_id', $customer_id)->get();


    		return view('frontend.view_product')->with(['prod' => $prod, 'data'=> 'View Product', 'cart'=> $cart]);
    }
}
