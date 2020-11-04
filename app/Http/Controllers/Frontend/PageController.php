<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CompanyDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
	
   public function aboutUs()
   {
      $company = CompanyDetails::first();
   	return view('frontend.about_us')->with([
         'data'=>'About Us', 
         'company'=>$company
      ]);
   }

   public function termsAndConditions()
   {
       $company = CompanyDetails::first();
   	return view('frontend.terms_and_conditions')->with([
         'data' => 'Terms and Conditions',
         'company' => $company
      ]);
   }

   public function cancelAndReturn()
   {
      $company = CompanyDetails::first();
   	return view('frontend.cancel_and_return')->with([
         'data' => 'Return Policy',
         'company' => $company
      ]);
   } 
}
