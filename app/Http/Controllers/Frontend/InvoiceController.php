<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:customer');
    }

    public function viewInvoice($invoice)
    {
    	$previous_url = url()->previous();

    	return view('frontend.invoice_detail', compact('invoice', 'previous_url'));
    }
}
