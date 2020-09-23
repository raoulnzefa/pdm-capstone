<?php

namespace App\Http\Controllers\Api;

use App\Models\Discount;
use App\Http\Controllers\Traits\UserLogs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiscountController extends Controller
{
	use UserLogs;

	public function getDiscount()
	{
		$discount = Discount::first();

		return response()->json($discount);
	}

	public function setDiscount(Request $request)
	{
		$request->validate([
			'order_quantity' => 'required',
			'discount_percent' => 'required'
		]);

		date_default_timezone_set("Asia/Manila");
		
		$discount = new Discount();

		$discount->order_quantity = (int)$request->order_quantity;
		$discount->discount_percent = (int)$request->discount_percent;
		$discount->is_disabled = (int)$request->status;
		$discount->save();

		$array_params = [
         'id' => $request->admin_id,
         'action' => 'Set discount'
     	];

      $this->createUserLog($array_params);

      $response = array('success'=>true);

      return response()->json($response);
	}

	public function updateDiscount(Request $request, Discount $discount)
	{
		$request->validate([
			'order_quantity' => 'required',
			'discount_percent' => 'required'
		]);

		date_default_timezone_set("Asia/Manila");

		$discount->order_quantity = (int)$request->order_quantity;
		$discount->discount_percent = (int)$request->discount_percent;
		$discount->is_disabled = (int)$request->status;
		$discount->update();

		$array_params = [
         'id' => $request->admin_id,
         'action' => 'Update discount'
     	];

      $this->createUserLog($array_params);

      $response = array('success'=>true);

      return response()->json($response);
	}
}
