<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CompanyDetails;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class InvoiceController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:customer');
    }

    public function generateInvoice(Request $request, $order)
    {

        date_default_timezone_set("Asia/Manila");

        $invoice = Invoice::where('order_number', $order)->first();

        $invoiceProduct = InvoiceProduct::where('invoice_number', $invoice->number)->get();

        $data = [
            'title' => 'Invoice',
            'invoice' => $invoice,
            'invoiceProduct' => $invoiceProduct,
            'company' => CompanyDetails::first()
        ];

        $pdf = PDF::loadView('frontend.invoice_pdf',$data);  
        $pdf->getDomPDF()->set_option("enable_php", true);
      
        return $pdf->stream();
    }
}
