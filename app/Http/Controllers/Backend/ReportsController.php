<?php

namespace App\Http\Controllers\Backend;

use App\Models\Product;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:admin');
    }

    public function salesReport()
    {
        return view('backend.reports.sales_report');
    }

    public function inventoryReportIndex()
    {
    	return view('backend.reports.inventory_report');
    }

    public function productsReportIndex()
    {
    	return view('backend.reports.products_report');
    }
    
    public function bestSelling()
    {
        return view('backend.reports.best_selling');
    }
}
