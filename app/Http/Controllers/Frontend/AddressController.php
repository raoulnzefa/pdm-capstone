<?php

namespace App\Http\Controllers\Frontend;

use App\Province;
use App\Municipality;
use App\Barangay;
use App\ShippingRate;
use App\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
	public function __construct()
   {
   	$this->middleware('auth:customer');
   }

   public function index()
   {
      $data = 'Address';
      $addresses = Address::get();
   	return view('frontend.address.index', compact('address','addresses'));
   }

   public function create()
   {
      $provinces = Province::all();
      $municipalities = Municipality::all();
      $barangays = Barangay::all();

      $data = 'Create Address';

   	return view('frontend.address.create', compact('provinces', 'municipalities', 'barangays', 'data'));
   }

   public function edit(Address $address)
   {
      $provinces = Province::all();
      $municipalities = Municipality::all();
      $barangays = Barangay::all();

      $data = 'Edit Address';

      return view('frontend.address.edit', compact(
            'provinces', 
            'municipalities', 
            'barangays', 
            'data',
            'address'
         ));
   }
}
