<?php

namespace App\Http\Controllers\Api;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('search'))
        {
            // search value
            $search_val = $request->query('search');

            $invoices = Invoice::where('number','=',$search_val)->paginate(10);

            // appends to pagination
            $invoices->appends($request->only('search'));    
        }
        else
        {
            $invoices = Invoice::orderBy('number','desc')->paginate(10);
        }
        
        return response()->json($invoices);
    }

    public function viewInvoice($invoice)
    {
        $invoice_data = Invoice::where('number','=',$invoice)
                        ->with('invoiceProducts', 'order.customer')->first();

        return response()->json($invoice_data);
    }

    public function invoiceDetails($invoice)
    {
    	// get invoice details
    	$invoice_details = Invoice::where('number', $invoice)
    						->with('order.orderProducts.returnProduct.returnOrder', 'order.customer', 'order.returnOrder.returnTotal', 'order.returnOrder.reason')->first();

    	return response()->json(['details' => $invoice_details]);
    }

    public function bestSellingProduct()
    {
    	$best_selling = InvoiceProduct::select(DB::raw(
                                    'inventory_sku,
                                    SUM(quantity) AS total_qty,
                                    SUM(total) AS total_amount,
                                    product_name,
                                    price'))
    					->with('inventory.product.category')
                        ->groupBy('inventory_sku', 'product_name', 'price')
                        ->orderBy('total_qty', 'DESC')
                        ->having('total_qty', '>', 20)
                        ->limit(10)
                        ->get();

        return response()->json($best_selling);
    }
}
