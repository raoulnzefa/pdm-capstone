<?php

namespace App\Http\Controllers\Backend;

use App\Models\CompanyDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyDetailsController extends Controller
{
   public function __construct()
   {
   	$this->middleware('auth:admin');
   }

   public function index()
   {
   	return view('backend.company_details.index')->with(['data'=>'Company Details', 'company' => CompanyDetails::first() ]);
   }
}
