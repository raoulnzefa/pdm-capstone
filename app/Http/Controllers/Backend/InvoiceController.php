<?php

namespace App\Http\Controllers\Backend;

use App\Order;
use App\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:admin');
    }

    public function index()
    {
    	return view('backend.invoice.index');
    }

    public function viewInvoice($invoice)
    {
        $previous_url = url()->previous();

    	return view('backend.invoice.view_invoice', compact('invoice', 'previous_url'));
    }
}
