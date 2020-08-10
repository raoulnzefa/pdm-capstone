<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReturnOrderController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:admin');
    }

    public function returnDetails($return_order)
    {
    	return view('backend.return_order.return_details', compact('return_order'));
    }
}
