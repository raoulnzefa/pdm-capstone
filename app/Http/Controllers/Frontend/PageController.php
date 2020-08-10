<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function aboutUs()
    {
    	return view('frontend.about_us')->with('data','About Us');
    }

    
}
