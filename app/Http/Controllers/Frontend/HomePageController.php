<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Customer;
use App\Models\CompanyDetails;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\TestHtmlTemplateEmail;
use Company;

class HomePageController extends Controller
{   
    public function index()
    {   

      $data = 'Home';
      $products = Product::limit(8)->orderBy('id')->get();
      $company = CompanyDetails::first();
    	return view('frontend.home')->with([
    		'data' => $data,
    		'products' => $products,
    		'company' => $company
    	]);
       
    }
}
