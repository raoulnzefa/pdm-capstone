<?php

namespace App\Http\Controllers\Backend;

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
 		return view('backend.shipping_rate.index', compact('data'));
 	}
}
