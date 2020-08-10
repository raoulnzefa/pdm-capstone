<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\Customer;
use App\Sales;
use App\Invoice;
use App\InvoiceProduct;
use App\Inventory;
use DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:admin');
	}

    public function index()
    {
        $data = 'Dashboard';
    	$admin = Auth::guard('admin')->user();

        date_default_timezone_set("Asia/Manila");

    	$daily = DB::table('invoice_products as inv_p')
                ->selectRaw('inv_p.*, sum(total) as totalAmount')
                ->whereRaw('Date(inv_p.created_at) = CURDATE()')
                ->groupBy('inv_p.id')
                ->get();
        $daily_sales = number_format($daily->sum('totalAmount'), 2);;

        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        
        $weekly = DB::table('invoice_products as inv_p')
                ->selectRaw('inv_p.*, sum(total) as totalAmount')
                ->whereBetween('inv_p.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfweek()])
                ->groupBy('inv_p.id')
                ->get();

        $weekly_sales = number_format($weekly->sum('totalAmount'), 2);


        $monthly = DB::table('invoice_products as inv_p')
                ->selectRaw('inv_p.*, sum(total) as totalAmount')
                ->whereRaw('MONTH(inv_p.created_at) = ?',[date('m')])
                ->groupBy('inv_p.id')
                ->get();

        $monthly_sales = number_format($monthly->sum('totalAmount'), 2);

        $yearly = DB::table('invoice_products as inv_p')
                ->selectRaw('inv_p.*, sum(total) as totalAmount')
                ->whereRaw('YEAR(inv_p.created_at) = ?',[date('Y')])
                ->groupBy('inv_p.id')
                ->get();

        $yearly_sales = number_format($yearly->sum('totalAmount'), 2);


    	return view('backend.dashboard', compact(
    		'admin',
    		'daily_sales',
    		'weekly_sales',
    		'monthly_sales',
            'yearly_sales',
            'data'
    	));
    }
}
