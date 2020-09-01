<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;

class HomePageController extends Controller
{   
    public function index()
    {   

      $data = 'Home';
      $products = Product::limit(8)->get();

    	return view('frontend.home', compact('data', 'products'));

       
    }
}
