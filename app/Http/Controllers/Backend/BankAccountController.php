<?php

namespace App\Http\Controllers\Backend;

use App\Models\CompanyDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankAccountController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:admin');
    }

    public function index()
    {
    	$data = 'Bank account';
    	$company = CompanyDetails::first();
    	return view('backend.bank_account.index', compact('data','company'));
    }
}
