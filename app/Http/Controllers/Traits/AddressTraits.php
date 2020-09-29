<?php

namespace App\Http\Controllers\Traits;

use App\Models\CustomerAddress;

trait AddressTraits
{
	public function createAddress($array_params)
	{
		date_default_timezone_set("Asia/Manila");
      
            $address = new CustomerAddress();
            $address->customer_id = $array_params['customer_id'];
            $address->address_name = str_random(4);
            $address->firstname = $array_params['firstname'];
            $address->lastname = $array_params['lastname'];
            $address->street = $array_params['street'];
            $address->barangay = $array_params['barangay'];
            $address->municipality = $array_params['municipality'];
            $address->province = $array_params['province'];
            $address->barangay_id = $array_params['barangay_id'];
            $address->municipality_id = $array_params['municipality_id'];
            $address->province_id = $array_params['province_id'];
            $address->zip_code = $array_params['zip_code'];
            $address->mobile_no = $array_params['mobile_no'];
            $address->save();

            $addrUpdate = CustomerAddress::where('id', $address->id)->first();
            $addrUpdate->address_name = 'Address '.$address->id;
            $addrUpdate->update();

	}
}