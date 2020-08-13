<?php

namespace App\Http\Controllers\Api;

use App\Models\Province;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
   public function saveAddress(Request $request)
   {
   	$request->validate(
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

      $province_name = Province::where('id', $request->province)->first()->name;
      $municipality_name = Municipality::where('id', $request->municipality)->first()->name;
      $barangay_name = Barangay::where('id', $request->barangay)->first()->name;

   	$address = new Address();
   	$address->customer_id = $request->customer_id;
   	$address->address_name = str_random(4);
      $address->firstname = ucfirst($firstname);
      $address->lastname = ucfirst($lastname);
   	$address->street = $request->street;
   	$address->province = $province_name;
   	$address->municipality = $municipality_name;
   	$address->barangay = $barangay_name;
      $address->province_id = $request->province;
      $address->municipality_id = $request->municipality;
      $address->barangay_id = $request->barangay;
   	$address->zip_code = $request->zip_code;
      $address->mobile_no = $request->mobile_no;
   	$address->save();

   	$addr = Address::where('id', $address->id)->first();

   	$addr->address_name = 'Address '.$address->id;
   	$addr->update();

   	$response = [
   		'success' => true,
   		'redirect_back' => $redirect_back
   	];

   	return response()->json($response);

   }

   public function updateAddress(Request $request, Address $address)
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

      $province_name = Province::where('id', $request->province)->first()->name;
      $municipality_name = Municipality::where('id', $request->municipality)->first()->name;
      $barangay_name = Barangay::where('id', $request->barangay)->first()->name;

      $address->firstname = ucfirst($firstname);
      $address->lastname = ucfirst($lastname);
   	$address->street = $request->street;
   	$address->province = $province_name;
      $address->municipality = $municipality_name;
      $address->barangay = $barangay_name;
      $address->province_id = $request->province;
      $address->municipality_id = $request->municipality;
      $address->barangay_id = $request->barangay;
   	$address->zip_code = $request->zip_code;
      $address->mobile_no = $request->mobile_no;
   	$address->update();

   	$response = [
   		'success' => true,
   		'redirect_back' => $redirect_back
   	];

   	return response()->json($response);

   }

   public function deleteAddress(Address $address)
   {
   	if ($address->delete())
     	{
         $response = array('deleted'=>true);
         
     	}

     	return response()->json($response);
   }

}
