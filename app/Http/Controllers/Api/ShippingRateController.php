<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Traits\UserLogs;

class ShippingRateController extends Controller
{
	use UserLogs;

   public function getShippingRate()
   {
   	$shipping_rate = ShippingRate::first();

   	return response()->json($shipping_rate);

   }

   public function setShippingRate(Request $request)
   {
   	  $request->validate([
        'flat_rate' => "required|regex:^[1-9][0-9]+^"
      ],
      [
        'flat_rate.required' => 'The flat rate field is required.',
        'flat_rate.regex' => 'The flat rate is invalid.'
      ]);

   	  date_default_timezone_set("Asia/Manila");

   	$shipping_rate = new ShippingRate();
   	$shipping_rate->flat_rate = round($request->flat_rate, 2);
   	$shipping_rate->save();

   	$array_params = [
         'id' => $request->admin_id,
         'action' => 'Set shipping rate'
     	];

      $this->createUserLog($array_params);

      $response = array('success'=>true);

      return response()->json($response);
   }

   public function updateShippingRate(Request $request, ShippingRate $shippingRate)
   {
    
      $request->validate([
        'flat_rate' => "required|regex:^[1-9][0-9]+^"
      ],
      [
        'flat_rate.required' => 'The flat rate field is required.',
        'flat_rate.regex' => 'The flat rate is invalid.'
      ]);

      date_default_timezone_set("Asia/Manila");

   	$shippingRate->flat_rate = round($request->flat_rate, 2);
   	$shippingRate->update();

   	$array_params = [
         'id' => $request->admin_id,
         'action' => 'Updated shipping rate'
     	];

      $this->createUserLog($array_params);

      $response = array('success'=>true);

      return response()->json($response);
   }
}
