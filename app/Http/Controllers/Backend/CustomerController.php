<?php

namespace App\Http\Controllers\Backend;

use App\Models\CompanyDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:admin');
    }

    public function index()
    {
        $data = 'Customers';
        $company = CompanyDetails::first();
    	return view('backend.customers.index', compact('data','company'));
    }

    public function list()
    {
    	return view('backend.customers.list');
    }

    public function view($customerId)
    {
        $data = 'View Customer';
        $company = CompanyDetails::first();
        return view('backend.customers.details', compact('data', 'customerId','company'));
    }
}
