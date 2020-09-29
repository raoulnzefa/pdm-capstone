<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function sales()
    {
        // $sales = DB::table('invoice_products as invp')
        //     ->leftJoin('inventories', 'invp.inventory_number', '=','inventories.number')
        //     ->leftJoin('products', 'inventories.product_number', '=', 'products.number')
        //     ->leftJoin('categories', 'products.category_id', '=','categories.id')
        //     ->selectRaw('invp.*, sum(invp.quantity) quantity, FORMAT(sum(invp.total),2) total, categories.name category')
        //     ->whereRaw('MONTH(invp.created_at) = MONTH(CURDATE())')
        //     ->whereRaw('YEAR(invp.created_at) = YEAR(CURDATE())')
        //     ->groupBy('category')
        //     ->get();

        return response()->json($sales);
    }

    public function searchSales(Request $request)
    {
        $request->validate([
            'from_date' => 'required',
            'to_date'   => 'required'
        ]);

        // set timezone
        date_default_timezone_set("Asia/Manila");
        //return response()->json($request->all());
        $now = date('F d, Y h:i A');

        $customFromDate = strtotime($request->from_date);
        $customToDate = strtotime($request->to_date);
        $from_date = $request->from_date." 00:00:00";
        $to_date = $request->to_date." 23:59:59";


        //$date = $request->date_today;

        $formatted_from_date = date('F d, Y', strtotime($from_date));
        $formatted_to_date = date('F d, Y', strtotime($to_date));

        // get search result
        // $sales_result = DB::table('invoice_products as invp')
        //     ->leftJoin('inventories', 'invp.inventory_sku', '=','inventories.sku')
        //     ->leftJoin('products', 'inventories.product_sku', '=', 'products.sku')
        //     ->leftJoin('categories', 'products.category_id', '=','categories.id')
        //     ->selectRaw('invp.*, sum(invp.quantity) quantity, FORMAT(sum(invp.total),2) total, categories.name category')
        //     ->groupBy('invp.inventory_sku')
        //     ->whereBetween('invp.created_at', [$from_date, $to_date])
        //     ->get();
        // $sales_result = DB::table('invoices as inv')
        //     ->select('inv.number','invp.inventory_sku', 'invp.product_name','invp.quantity', 'invp.total', 'inv.created')
        //     ->join('invoice_products as invp', 'inv.number', '=', 'invp.invoice_number')
        //     ->whereBetween('inv.created', [$from_date, $to_date])
        //     ->get();

        // $sales_result = Invoice::join('invoice_products', function($join) use($from_date, $to_date) {
        //     $join->on('invoices.number', '=', 'invoice_products.invoice_number')
        //     ->whereBetween('invoices.created', [$from_date, $to_date]);
        // })->get();
        
        // get gross sales
        // $gross_sales = Invoice::where(function($query) use($from_date, $to_date) {
        //                 $query->whereBetween('created', [$from_date, $to_date]);
        //             })
        //             ->sum('total');
        
        $gross_sales = InvoiceProduct::where(function($query) use($from_date, $to_date) {
                        $query->whereBetween('created_at', [$from_date, $to_date]);
                    })
                    ->sum('total');

        // get discount
        $discount = Invoice::where(function($query) use($from_date, $to_date) {
                        $query->whereBetween('created', [$from_date, $to_date]);
                    })
                    ->sum('discount');
       


        // net sales
        $net_sales = $gross_sales - $discount;

        return response()->json([
            'sales'=>$sales_result ,
            'gross_sales' => number_format($gross_sales,2),
            'discount' => number_format($discount,2),
            'net_sales'=> number_format($net_sales,2),
            'from_date'=> $formatted_from_date,
            'to_date'=> $formatted_to_date,
            'date_now'=>$now
        ]);
    }

   
}
