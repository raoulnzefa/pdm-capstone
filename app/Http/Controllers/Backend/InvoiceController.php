<?php

namespace App\Http\Controllers\Backend;

use App\Models\Order;
use App\Models\Invoice;
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

    public function viewInvoice($order)
    {
        $invoice = Invoice::where('order_number',$order)
            ->with('order.shipping','invoiceProducts')
            ->first();

        $data = 'Invoice';

    	return view('backend.invoice.view_invoice', compact('invoice','data'));
    }
}
