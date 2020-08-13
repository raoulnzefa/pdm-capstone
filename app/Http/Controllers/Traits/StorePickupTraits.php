<?php

namespace App\Http\Controllers\Traits;

use App\Models\StorePickup;

trait StorePickupTraits {
	
	public function createStorePickup($array_params)
	{
		// set timezone
            date_default_timezone_set("Asia/Manila");

            $store_pickup = new StorePickup();
            $store_pickup->order_number = $array_params['order_number'];
            $store_pickup->pickup_firstname = $array_params['pickup_firstname'];
            $store_pickup->pickup_lastname = $array_params['pickup_lastname'];
            $store_pickup->pickup_mobile_no = $array_params['pickup_mobile_no'];
            $store_pickup->pickup_reserved_days = $array_params['pickup_reserved_days'];
            $store_pickup->pickup_reserved_until = $array_params['pickup_reserved_until'];
            $store_pickup->save();

	}
}