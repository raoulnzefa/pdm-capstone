<?php

namespace App\Http\Controllers\Frontend;

use App\Models\ShippingRate;
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
   	return view('frontend.address.index', compact('data'));
   }

   public function create()
   {
      $data = 'Create Address';

   	return view('frontend.address.create', compact('data'));
   }

   public function edit(CustomerAddress $address)
   {
      $data = 'Edit Address';

      return view('frontend.address.edit', compact(
            'data',
            'address'
         ));
   }
}
