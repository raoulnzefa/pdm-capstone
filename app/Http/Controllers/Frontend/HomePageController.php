<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomePageController extends Controller
{   
    public function index()
    {   
      $data = 'Home';
      $products = Product::limit(8)->orderBy('id')->get();

    	return view('frontend.home', compact('data', 'products'));

       
    }
}
