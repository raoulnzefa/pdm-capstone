<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Traits\UserLogs;
use App\Models\Voucher;
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
   		'voucher_description' => 'required',
         'voucher_discount_percent' => 'required',
   		'voucher_start' => 'required',
   		'voucher_end' => 'required'
   	]);

      date_default_timezone_set("Asia/Manila");

      $voucher = new Voucher();
      $voucher->voucher_code = $request->voucher_code;
      $voucher->voucher_description = $request->voucher_description;
      $voucher->voucher_discount_percent = (int)$request->voucher_discount_percent;
      $voucher->voucher_start = date('Y-m-d', strtotime($request->voucher_start));
      $voucher->voucher_end = date('Y-m-d 23:59:59', strtotime($request->voucher_end));
      $voucher->save();


      $array_params = [
            'id' => $request->admin_id,
            'action' => 'Created voucher. Voucher ID: '.$voucher->id
      ];

      $this->createUserLog($array_params);

      $response = array('success'=>true);
        
      return response()->json($response);
   }
   public function getVouchers()
   {
      $vouchers = Voucher::get();

      return response()->json($vouchers);
   }

   public function updateVoucher(Request $request, Voucher $voucher)
   {
      $request->validate([
         'voucher_code' => [
               'required',
               Rule::unique('vouchers')->ignore($voucher->id)
                  ->where(function($query) use ($request) {
                     return $query->where('voucher_code',$request->voucher_code);
                  })
         ],
         'voucher_description' => 'required',
         'voucher_discount_percent' => 'required',
         'voucher_start' => 'required',
         'voucher_end' => 'required'
      ]);

      $voucher->voucher_code = $request->voucher_code;
      $voucher->voucher_description = $request->voucher_description;
      $voucher->voucher_discount_percent = (int)$request->voucher_discount_percent;
      $voucher->voucher_start = date('Y-m-d', strtotime($request->voucher_start));
      $voucher->voucher_end = date('Y-m-d 23:59:59', strtotime($request->voucher_end));
      $voucher->save();


      $array_params = [
            'id' => $request->admin_id,
            'action' => 'Updated voucher. Voucher ID: '.$voucher->id
      ];

      $this->createUserLog($array_params);

      $response = array('success'=>true);
        
      return response()->json($response);
   }
}
