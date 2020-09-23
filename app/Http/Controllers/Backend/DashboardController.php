<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Customer;
use App\Models\Sales;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Inventory;
use App\Http\Controllers\Traits\InventoryManager;
use DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    use InventoryManager;

	public function __construct()
	{
		$this->middleware('auth:admin');
	}

    public function index()
    {
        // check for overdue orders
        $overdue = Order::where('order_status','!=','Overdue')->where(function($query) {
            $query->whereRaw('order_for_pickup < CURDATE()')
            ->orWhereRaw('order_due_payment < CURDATE()');
        })->get();

        foreach ($overdue as $item)
        {
            $this->restockOrder($item->number);

            $order = Order::where('number', $item->number)->first();
            $order->order_status = 'Overdue';
            $order->order_restocked = 1;
            $order->viewed = 0;
            $order->order_remarks = 'Restocked';
            $order->update();
        }

        $data = 'Dashboard';
    	$admin = Auth::guard('admin')->user();

        date_default_timezone_set("Asia/Manila");

    	$daily = DB::table('invoice_products as inv_p')
                ->selectRaw('inv_p.*, sum(total) as totalAmount')
                ->whereRaw('Date(inv_p.created_at) = CURDATE()')
                ->groupBy('inv_p.id')
                ->get();

        $daily_discount = DB::table('invoices')
                ->selectRaw('sum(discount) as totalDiscount')
                ->whereRaw('Date(invoices.created_at) = CURDATE()')
                ->get();

        $daily_sales = number_format(($daily->sum('totalAmount') - $daily_discount->sum('totalDiscount')),2);

        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        
        $weekly = DB::table('invoice_products as inv_p')
                ->selectRaw('inv_p.*, sum(total) as totalAmount')
                ->whereBetween('inv_p.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfweek()])
                ->groupBy('inv_p.id')
                ->get();

        $weekly_discount = DB::table('invoices')
                ->selectRaw('sum(discount) as totalDiscount')
                ->whereBetween('invoices.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfweek()])
                ->get();

        $weekly_sales = number_format(($weekly->sum('totalAmount') - $weekly_discount->sum('totalDiscount')), 2);


        $monthly = DB::table('invoice_products as inv_p')
                ->selectRaw('inv_p.*, sum(total) as totalAmount')
                ->whereRaw('MONTH(inv_p.created_at) = ?',[date('m')])
                ->groupBy('inv_p.id')
                ->get();

        $monthly_discount = DB::table('invoices')
                ->selectRaw('sum(discount) as totalDiscount')
                ->whereRaw('MONTH(invoices.created_at) = ?',[date('m')])
                ->get();

        $monthly_sales = number_format(($monthly->sum('totalAmount') - $monthly_discount->sum('totalDiscount')), 2);

        $yearly = DB::table('invoice_products as inv_p')
                ->selectRaw('inv_p.*, sum(total) as totalAmount')
                ->whereRaw('YEAR(inv_p.created_at) = ?',[date('Y')])
                ->groupBy('inv_p.id')
                ->get();

        $yearly_discount = DB::table('invoices')
                ->selectRaw('sum(discount) as totalDiscount')
                ->whereRaw('YEAR(invoices.created_at) = ?',[date('Y')])
                ->get();

        $yearly_sales = number_format(($yearly->sum('totalAmount') - $yearly_discount->sum('totalDiscount')), 2);


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
