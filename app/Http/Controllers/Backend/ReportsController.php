<?php

namespace App\Http\Controllers\Backend;

use App\Models\UserLog;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PdfReport;
use PDF;
use DB;

class ReportsController extends Controller
{

    public function __construct()
    {
    	$this->middleware('auth:admin');
    }

    public function generateInventory(Request $request)
    {
      date_default_timezone_set("Asia/Manila");

      $admin = Admin::where('id',$request->input('admin_id'))->first();

      $printed_by = $admin->first_name.' '.$admin->last_name;
      
      $now = date('F d, Y h:iA');

      if ($request->input('report_type') == 'in_stock')
      {
        $report_type = 'All Stocks';
        $inventories = Inventory::all();
        $total_qty = Inventory::sum('inventory_stock');

        $data = [
          'title' => 'Inventory Report',
          'inventories' => $inventories,
          'total' => $total_qty,
          'printed_by' => $printed_by,
          'date_printed' => $now,
          'report_type' => $report_type
        ];

      }
      elseif ($request->input('critical_level'))
      {
        $inventories = Inventory::where('inventory_stock', '<=', 'inventory_critical_level')->get();
        $report_type = 'Critical Level';

        $data = [
          'title' => 'Inventory Report',
          'inventories' => $inventories,
          'printed_by' => $printed_by,
          'date_printed' => $now,
          'report_type' => $report_type
        ];

      }
      else
      {
        $inventories = Inventory::where('inventory_stock', 0)->get();
        $report_type = 'Out of Stock';

        $data = [
          'title' => 'Inventory Report',
          'inventories' => $inventories,
          'printed_by' => $printed_by,
          'date_printed' => $now,
          'report_type' => $report_type
        ];

      }

      $pdf = PDF::loadView('backend.report_pdf.inventory_pdf',$data);  
      $pdf->getDomPDF()->set_option("enable_php", true);
      
      return $pdf->stream();
    }

    public function generateCustomer(Request $request)
    {
      date_default_timezone_set("Asia/Manila");

      $admin = Admin::where('id',$request->input('admin_id'))->first();

      $printed_by = $admin->first_name.' '.$admin->last_name;

      $now = date('F d, Y h:iA');

      $customers = Customer::all();

      $data = [
        'title' => 'Customer List',
        'printed_by' => $printed_by,
        'date_printed' => $now,
        'customers' => $customers
      ];

      $pdf = PDF::loadView('backend.report_pdf.customers_pdf',$data);  
      $pdf->getDomPDF()->set_option("enable_php", true);
      
      return $pdf->stream();

    }

    public function generateSales(Request $request)
    {
      $request->validate([
        'from_date' => 'required',
        'to_date'   => 'required'
      ]); 

      date_default_timezone_set("Asia/Manila");

      $admin = Admin::where('id',$request->input('admin_id'))->first();

      $printed_by = $admin->first_name.' '.$admin->last_name;

      $now = date('F d, Y h:iA');

      $from_date = date('Y-m-d', strtotime($request->from_date))." 00:00:00";
      $to_date = date('Y-m-d', strtotime($request->to_date))." 23:59:59"; 

      $sales = DB::table('invoice_products as invp')
          ->leftJoin('inventories', 'invp.inventory_number', '=','inventories.number')
          ->leftJoin('products', 'inventories.product_number', '=', 'products.number')
          ->leftJoin('categories', 'products.category_id', '=','categories.id')
          ->selectRaw('invp.*, sum(invp.quantity) quantity, FORMAT(sum(invp.total),2) total, categories.name category')
          ->groupBy('category')
          ->whereBetween('invp.created_at', [$from_date, $to_date])
          ->get();

      $total = InvoiceProduct::where(function($query) use($from_date, $to_date) {
                  $query->whereBetween('created_at', [$from_date, $to_date]);
              })
              ->sum('total');


      $date_range = 'From:  '.date('F d, Y', strtotime($request->from_date)).'   To:  '.date('F d, Y', strtotime($request->to_date));

      $data = [
        'title' => 'Sales Report',
        'printed_by' => $printed_by,
        'date_printed' => $now,
        'sales' => $sales,
        'date_range' => $date_range, 
        'total' => $total
      ];

      $pdf = PDF::loadView('backend.report_pdf.sales_pdf',$data);  
      $pdf->getDomPDF()->set_option("enable_php", true);
      
      return $pdf->stream();
    }

    public function generateAuditTrail(Request $request)
    {
      $request->validate([
        'from_date' => 'required',
        'to_date'   => 'required'
      ]); 

      date_default_timezone_set("Asia/Manila");

      $admin = Admin::where('id',$request->input('admin_id'))->first();

      $printed_by = $admin->first_name.' '.$admin->last_name;

      $now = date('F d, Y h:iA');

      $from_date = date('Y-m-d', strtotime($request->from_date))." 00:00:00";
      $to_date = date('Y-m-d', strtotime($request->to_date))." 23:59:59"; 

      $date_range = 'From:  '.date('F d, Y', strtotime($request->from_date)).'   To:  '.date('F d, Y', strtotime($request->to_date));

      $audit_trail = UserLog::whereBetween('log_created', [$from_date, $to_date])
          ->with('admin')
          ->get();


      $data = [
        'title' => 'Audit Trail',
        'date_range' => $date_range,
        'printed_by' => $printed_by,
        'date_printed' => $now,
        'audit_trail' => $audit_trail
      ];


      $pdf = PDF::loadView('backend.report_pdf.audit_trail_pdf',$data);  
      $pdf->getDomPDF()->set_option("enable_php", true);
      
      return $pdf->stream();
    }

    public function generateInvoice(Request $request)
    {
      date_default_timezone_set("Asia/Manila");

      $invoice = Invoice::where('order_number', $request->input('order_number'))->first();

      $invoiceProduct = InvoiceProduct::where('invoice_number', $invoice->number)->get();

      $data = [
        'title' => 'Invoice',
        'invoice' => $invoice,
        'invoiceProduct' => $invoiceProduct
      ];

      $pdf = PDF::loadView('backend.report_pdf.invoice_pdf',$data);  
      $pdf->getDomPDF()->set_option("enable_php", true);
      
      return $pdf->stream();
    }

    public function generateBestSelling(Request $request)
    {
      $request->validate([
        'from_date' => 'required',
        'to_date'   => 'required'
      ]); 

      date_default_timezone_set("Asia/Manila");

      $admin = Admin::where('id',$request->input('admin_id'))->first();

      $printed_by = $admin->first_name.' '.$admin->last_name;

      $now = date('F d, Y h:iA');

      $from_date = date('Y-m-d', strtotime($request->from_date))." 00:00:00";
      $to_date = date('Y-m-d', strtotime($request->to_date))." 23:59:59"; 

      $date_range = 'From:  '.date('F d, Y', strtotime($request->from_date)).'   To:  '.date('F d, Y', strtotime($request->to_date));

      $best_selling = DB::table('invoice_products as invp')
            ->leftJoin('inventories', 'invp.inventory_number', '=','inventories.number')
            ->leftJoin('products', 'inventories.product_number', '=', 'products.number')
            ->leftJoin('categories', 'products.category_id', '=','categories.id')
            ->selectRaw('invp.*, FORMAT(invp.price, 2) price, sum(invp.quantity) quantity, FORMAT(sum(invp.total),2) total, categories.name category')
            ->whereBetween('invp.created_at', [$from_date, $to_date])
            ->groupBy('category')
            ->orderBy('quantity', 'DESC')
            ->having('quantity', '>', 2)
            ->limit(5)
            ->get();

      $data = [
        'title' => 'Best Selling',
        'printed_by' => $printed_by,
        'date_printed' => $now,
        'date_range' => $date_range,
        'best_selling' => $best_selling
      ];

      $pdf = PDF::loadView('backend.report_pdf.best_selling_pdf',$data);  
      $pdf->getDomPDF()->set_option("enable_php", true);
      
      return $pdf->stream();

    }
  
}
