<?php

namespace App\Http\Controllers\Api;

use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerAddressController extends Controller
{
   public function getAddresses($customer)
   {
      $addresses = CustomerAddress::where('customer_id', $customer)->get();

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

      $addrCount = CustomerAddress::where('customer_id', (int)$request->customer_id)->count();

      $addrCount += 1;

   	$address = new CustomerAddress();
   	$address->customer_id = $request->customer_id;
   	$address->address_name = 'Address '.$addrCount;
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

      $addrCount = CustomerAddress::where('customer_id', $request->customer_id)->count();

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

   public function checkMobileNumber(Request $request)
   {
      $customer = CustomerAddress::where('mobile_no', $request->query('mobile'))->exists();

      return response()->json($customer);
   }

}
