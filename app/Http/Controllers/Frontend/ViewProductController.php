<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductWithVariant;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ViewProductController extends Controller
{
    public function __construct()
	{
		return $this->middleware('auth:customer');
	}

	public function viewProduct($category,$product)
    {   
        	$prod = Product::where('product_status',1)
        		->where('product_url', $product)
        		->with('productNoVariant.inventory','productWithVariants')
        		->first();

        	$variant = ProductWithVariant::where('variant_status',1)
        					->where('product_number', $prod->number)
                            ->with('inventory')
        					->first();


            $product_variants = ProductWithVariant::where('product_number',$prod->number)
                    ->with('inventory')
                    ->get();


    		return view('frontend.view_product')->with([
    			'prod' => $prod, 
    			'data'=> 'View Product',
    			'variant' => $variant,
                'product_variants' => $product_variants
    		]);
    }
}
