<?php

namespace App\Http\Controllers\Api;

use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerAddressController extends Controller
{
   public function getAddresses()
   {
      $addresses = CustomerAddress::get();

      return response()->json($addresses);
   }

   public function saveAddress(Request $request)
   {
   	$request->validate([
            'firstname' => 'required',
            'lastname'=> 'required',
            'street' => 'required',
            'province' =>  'required',
            'municipality' =>  'required',
            'barangay' =>  'required',
            'zip_code' =>  'required',
            'mobile_no' => 'required'
      ]);

      date_default_timezone_set("Asia/Manila");

      $redirect_back = route('customer_address');


   	$address = new CustomerAddress();
   	$address->customer_id = $request->customer_id;
   	$address->address_name = str_random(4);
      $address->firstname = ucfirst($request->firstname);
      $address->lastname = ucfirst($request->lastname);
   	$address->street = $request->street;
      $address->province = $request->province;
      $address->municipality = $request->municipality;
      $address->barangay = $request->barangay;
      $address->province_id = $request->province_id;
      $address->municipality_id = $request->municipality_id;
      $address->barangay_id = $request->barangay_id;
   	$address->zip_code = $request->zip_code;
      $address->mobile_no = $request->mobile_no;
   	$address->save();

   	$addr = CustomerAddress::where('id', $address->id)->first();

   	$addr->address_name = 'Address '.$address->id;
   	$addr->update();

   	$response = [
   		'success' => true,
   		'redirect_back' => $redirect_back
   	];

   	return response()->json($response);

   }

   public function updateAddress(Request $request, CustomerAddress $address)
   {

   	$request->validate([
            'street' => 'required',
            'province' =>  'required',
            'municipality' =>  'required',
            'barangay' =>  'required',
            'zip_code' =>  'required'
      ]);

      date_default_timezone_set("Asia/Manila");

      $redirect_back = route('customer_address');

      $address->firstname = ucfirst($request->firstname);
      $address->lastname = ucfirst($request->lastname);
   	$address->street = $request->street;
      $address->province = $request->province;
      $address->municipality = $request->municipality;
      $address->barangay = $request->barangay;
      $address->province_id = $request->province_id;
      $address->municipality_id = $request->municipality_id;
      $address->barangay_id = $request->barangay_id;
   	$address->zip_code = $request->zip_code;
      $address->mobile_no = $request->mobile_no;
   	$address->update();

   	$response = [
   		'success' => true,
   		'redirect_back' => $redirect_back
   	];

   	return response()->json($response);

   }

   public function deleteAddress(CustomerAddress $address)
   {
   	if ($address->delete())
     	{
         $response = array('deleted'=>true);
         
     	}

     	return response()->json($response);
   }

}
