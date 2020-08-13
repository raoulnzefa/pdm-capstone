<?php

namespace App\Http\Controllers\Api;

use App\Models\BankDepositSlip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankDepositSlipController extends Controller
{
 	public function confirmBankDepositSlip(Request $request)
	{
		//dd($request->order_number);
		// set timezone
      date_default_timezone_set("Asia/Manila");

		$bank_deposit_slip = BankDepositSlip::where('order_number', $request->order_number)->first();
		$bank_deposit_slip->is_confirmed = 1;
		$bank_deposit_slip->date_confirmed = date('Y-m-d H:i:s');
		$bank_deposit_slip->update();

		return response()->json(['success' => true]);

	}
}
