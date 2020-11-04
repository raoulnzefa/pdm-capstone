<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CompanyDetails;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerAddressController extends Controller
{
	public function __construct()
   {
   	$this->middleware('auth:customer');
   }

   public function index()
   {
      $data = 'Address';
      $company = CompanyDetails::first();
   	return view('frontend.address.index', compact('data','company'));
   }

   public function create()
   {
      $data = 'Create Address';
      $company = CompanyDetails::first();

   	return view('frontend.address.create', compact('data','company'));
   }

   public function edit(CustomerAddress $address)
   {
      
      $data = 'Edit Address';
      $company = CompanyDetails::first();
      return view('frontend.address.edit', compact(
            'data',
            'address',
            'company'
         ));
   }
}
