<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CompanyDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountDetailsController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:customer');
    }

    public function index()
    {

    	return view('frontend.customer.account_details')->with([
    		'data'=> 'Account Details',
    		'company' => CompanyDetails::first()
    	]);
    }
}
