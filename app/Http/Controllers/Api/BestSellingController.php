<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Inventoy;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class BestSellingController extends Controller
{
        public function getBestSelling(Request $request)
        {              
                 // set timezone
                date_default_timezone_set("Asia/Manila");
                //return response()->json($request->all());
                $now = date('F d, Y h:i A');

                $from_date = $request->from_date." 00:00:00";
                $to_date = $request->to_date." 23:59:59";

                $formatted_from_date = date('F d, Y', strtotime($from_date));
                $formatted_to_date = date('F d, Y', strtotime($to_date));

        	// $best_selling = InvoiceProduct::select(DB::raw('inventory_sku, product_name,  SUM(quantity) AS total_qty, SUM(total) AS total_amount'))
         //                ->
         //                ->whereBetween('created_at', [$from_date, $to_date])
         //                ->groupBy('inventory_sku', 'product_name')
         //                ->orderBy('total_qty', 'DESC')
         //                ->having('total_qty', '>', 4)
         //                ->limit(5)
         //                ->get();

                // $best_selling = DB::table('invoice_products as invp')
                //         ->leftJoin('inventories', 'invp.inventory_sku', '=','inventories.sku')
                //         ->leftJoin('products', 'inventories.product_sku', '=', 'products.sku')
                //         ->leftJoin('categories', 'products.category_id', '=','categories.id')
                //         ->selectRaw('invp.*, FORMAT(invp.price, 2) price, sum(invp.quantity) quantity, FORMAT(sum(invp.total),2) total, categories.name category')
                //         ->whereBetween('invp.created_at', [$from_date, $to_date])
                //         ->groupBy('invp.inventory_sku', 'invp.product_name')
                //         ->orderBy('quantity', 'DESC')
                //         ->having('quantity', '>', 2)
                //         ->limit(5)
                //         ->get();


                $gross_sales = InvoiceProduct::where(function($query) use($from_date, $to_date) {
                                $query->whereBetween('created_at', [$from_date, $to_date]);
                        })
                        ->sum('total');

                $response = [
                        'best_selling_result' => $best_selling,
                        'total_sales' => number_format($gross_sales,2),
                        'from_date'=> $formatted_from_date,
                        'to_date'=> $formatted_to_date,
                        'date_now'=>$now
                        ];        

                return response()->json($response);
        }
}
