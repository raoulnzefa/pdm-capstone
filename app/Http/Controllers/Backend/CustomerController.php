<?php

namespace App\Http\Controllers\Backend;

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
    	return view('backend.customers.index', compact('data'));
    }

    public function list()
    {
    	return view('backend.customers.list');
    }

    public function view($customerId)
    {
        $data = 'View Customer';
        return view('backend.customers.details', compact('data', 'customerId'));
    }
}
