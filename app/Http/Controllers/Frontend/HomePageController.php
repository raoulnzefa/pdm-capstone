<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
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
      $categories = Category::get();

    	return view('frontend.home', compact('data', 'categories'));

       
    }
}
