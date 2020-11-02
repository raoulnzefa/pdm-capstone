<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CompanyDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
	
   public function aboutUs()
   {
      $about_us = CompanyDetails::first()->about_us;
   	return view('frontend.about_us')->with(['data'=>'About Us', 'about_us'=>$about_us]);
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
