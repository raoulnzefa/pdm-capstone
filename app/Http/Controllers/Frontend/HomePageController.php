<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Customer;
use App\Models\CompanyDetails;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\TestHtmlTemplateEmail;
use Company;
use DB;

class HomePageController extends Controller
{   
    public function index()
    {   
      //return DB::select("select '2010-01-01 12:00:00'::date");
      //select '2010-01-01 12:00:00'::timestamp::date; Format to date only
      // DB::select('select localtimestamp(0)'); No milliseconds in timestamp
      // date_default_timezone_set("Asia/Manila");
      // $due_date_email = strftime("%A, %B %d, %Y %T", strtotime('+3 days'));
      // return $due_date_email;
      

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
