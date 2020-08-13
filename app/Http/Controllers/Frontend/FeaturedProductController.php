<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductNoVariant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;

class FeaturedProductController extends Controller
{
    public function index()
    {
    	$categories = Category::where('display','Enabled')->get();
    	$products = Product::where(['featured'=>'Yes', 'status'=>'Active'])->with('productNoVariant','productVariants')->paginate('9');


    	return view('frontend.featured_products')->with(['categories'=>$categories, 'products'=>$products]);
    }

    public function category($category)
    {
    	$category_id = Category::where('url',$category)->first()->id;
    	$categories = Category::where('display','Enabled')->get();
    	$products = Product::where(['featured'=>'Yes', 'status'=>'Active'])->paginate('9');

    	return view('frontend.featured_products')->with(['categories'=>$categories, 'products'=>$products]);
    }
}
