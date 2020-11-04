<?php

namespace App\Http\Controllers\Backend;

use App\Models\CompanyDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiscountController extends Controller
{
	public function __construct()
   {
   	$this->middleware('auth:admin');
   }

   public function index()
   {
   	$data = 'Discount';
   	$company = CompanyDetails::first();
   	return view('backend.discount.index', compact('data','company'));
   }
}
