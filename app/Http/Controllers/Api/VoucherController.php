<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Traits\UserLogs;
use App\Models\Voucher;
use App\Models\Discount;
use App\Models\FreeShipping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
	use UserLogs;

   public function createVoucher(Request $request)
   {
   	$request->validate([
   		'voucher_code' => 'required|unique:vouchers',
   		'voucher_type' => 'required',
   		'voucher_start' => 'required',
   		'voucher_end' => 'required'
   	]);

      date_default_timezone_set("Asia/Manila");

      $voucher = new Voucher();
      $voucher->voucher_code = $request->voucher_code;
      $voucher->voucher_type = $request->voucher_type;
      $voucher->voucher_start = date('Y-m-d', strtotime($request->voucher_start));
      $voucher->voucher_end = date('Y-m-d 23:59:59', strtotime($request->voucher_end));
      $voucher->save();

      if ($request->voucher_type == 'Discount')
      {
      	$discount = new Discount();
      	$discount->voucher_id = $voucher->id;
      	$discount->discount_percent = ($request->filled('voucher_discount')) 
      											? (int)$request->voucher_discount
      											: 0;
      	$discount->save();
      }
      else
      {
      	$free_shipping = new FreeShipping();
      	$free_shipping->voucher_id = $voucher->id;
      	$free_shipping->save();
      }

      $array_params = [
            'id' => $request->admin_id,
            'action' => 'Created voucher. Voucher ID: '.$voucher->id
      ];

      $this->createUserLog($array_params);

      $response = array('success'=>true);
        
      return response()->json($response);
   }
}
