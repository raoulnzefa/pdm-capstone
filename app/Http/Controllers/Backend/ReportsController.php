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
        $data = 'Sales';
        return view('backend.reports.sales_report', compact('data'));
    }

    public function inventoryReportIndex()
    {
        $data = 'Inventory';
    	return view('backend.reports.inventory_report', compact('data'));
    }

    public function productsReportIndex()
    {
        $data = 'Products'
    	return view('backend.reports.products_report', compact('data'));
    }
    
    public function bestSelling()
    {
        $data = 'Best Selling';
        return view('backend.reports.best_selling', compact('data'));
    }

    public function customerList()
    {
        $data = 'Customer List';
        return view('backend.reports.customer_list', compact('data'));
    }

    public function userLogs()
    {
        $data = 'User Logs';
        return view('backend.user_logs.index', compact('data'));
    }
}
