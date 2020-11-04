<?php

namespace App\Http\Controllers\Backend;

use App\Models\CompanyDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingRateController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:admin');
	}

 	public function index()
 	{
 		$data = 'Shipping rate';
 		$company = CompanyDetails::first();
 		return view('backend.shipping_rate.index', compact('data','company'));
 	}
}
