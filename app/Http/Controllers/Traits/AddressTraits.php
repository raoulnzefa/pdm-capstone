<?php

namespace App\Http\Controllers\Traits;

use App\Models\CustomerAddress;

trait AddressTraits
{
	public function createAddress($array_params)
	{
		date_default_timezone_set("Asia/Manila");
            
            $addrCounter = CustomerAddress::where('customer_id', $array_params['customer_id'])->count();

            $addrCounter += 1;

            $address = new CustomerAddress();
            $address->customer_id = $array_params['customer_id'];
            $address->address_name = 'Address '.$addrCounter;
            $address->firstname = $array_params['firstname'];
            $address->lastname = $array_params['lastname'];
            $address->street = $array_params['street'];
            $address->barangay = $array_params['barangay'];
            $address->municipality = $array_params['municipality'];
            $address->province = $array_params['province'];
            $address->zip_code = $array_params['zip_code'];
            $address->mobile_no = $array_params['mobile_no'];
            $address->save();

	}
}