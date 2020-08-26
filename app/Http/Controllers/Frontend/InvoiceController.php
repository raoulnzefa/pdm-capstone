<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:customer');
    }

    public function viewInvoice($order)
    {

    	$data = 'Invoice';
    	$previous_url = url()->previous();
    	$invoice = Invoice::where('order_number', $order)
    		->with('order.shipping','invoiceProducts')
    		->first();

    	return view('frontend.invoice_detail', compact('invoice', 'previous_url','data'));
    }
}
