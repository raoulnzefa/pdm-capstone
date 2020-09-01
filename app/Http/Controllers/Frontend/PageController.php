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

   public function termsAndConditions()
   {
   	return view('frontend.terms_and_conditions')->with('data','Terms and Conditions');
   }

   public function cancelAndReturn()
   {
   	return view('frontend.cancel_and_return')->with('data','Cancel and Return');
   } 
}
