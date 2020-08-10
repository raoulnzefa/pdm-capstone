<?php

namespace App\Http\Controllers\Traits;

use App\Delivery;

trait DeliveryTraits
{
	public function createDelivery($array_params)
	{
		date_default_timezone_set("Asia/Manila");

      $delivery = new Delivery();
      $delivery->order_number = $array_params['order_number'];
      $delivery->delivery_firstname = $array_params['delivery_firstname'];
      $delivery->delivery_lastname = $array_params['delivery_lastname'];
      $delivery->delivery_street = $array_params['delivery_street'];
      $delivery->delivery_barangay = $array_params['delivery_barangay'];
      $delivery->delivery_municipality = $array_params['delivery_municipality'];
      $delivery->delivery_province = $array_params['delivery_province'];
      $delivery->delivery_zip_code = $array_params['delivery_zip_code'];
      $delivery->delivery_mobile_no = $array_params['delivery_mobile_no'];
      $delivery->delivery_discount_amount = $array_params['delivery_discount_amount'];
      $delivery->delivery_fee = $array_params['delivery_fee'];
      $delivery->delivery_process_days = $array_params['delivery_process_days'];
      $delivery->delivery_estimated_date = $array_params['delivery_estimated_date'];
      $delivery->save();
	}
}