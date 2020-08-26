<?php

namespace App\Http\Controllers\Traits;

use App\Models\ShippingAddress;

trait ShippingAddressTraits
{
	public function createShipping($array_params)
	{
		date_default_timezone_set("Asia/Manila");

      $shipping = new ShippingAddress();
      $shipping->order_number = $array_params['order_number'];
      $shipping->shipping_firstname = $array_params['shipping_firstname'];
      $shipping->shipping_lastname = $array_params['shipping_lastname'];
      $shipping->shipping_street = $array_params['shipping_street'];
      $shipping->shipping_barangay = $array_params['shipping_barangay'];
      $shipping->shipping_municipality = $array_params['shipping_municipality'];
      $shipping->shipping_province = $array_params['shipping_province'];
      $shipping->shipping_zip_code = $array_params['shipping_zip_code'];
      $shipping->shipping_mobile_no = $array_params['shipping_mobile_no'];
      $shipping->save();
	}
}