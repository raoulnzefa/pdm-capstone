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
        $overdue = Order::where('order_status','For pickup')
            ->where(function($query) {
                    $query->whereRaw('order_for_pickup < CURRENT_DATE');
            });

        $overdue = $overdue->where('order_status','Pending payment')
            ->where(function($query) {
                    $query->whereRaw('order_due_payment < CURRENT_DATE');
            });

        $overdue = $overdue->get();
        

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
                ->selectRaw("inv_p.*, sum(total) as totalAmount")
                ->whereRaw("date_trunc('day', created_at) = CURRENT_DATE")
                ->groupBy('inv_p.id')
                ->get();

        //$daily = DB::select("select *, sum(total) as totalAmount from invoice_products where date_trunc('day',created_at) = current_date group by id");

        $daily_discount = DB::table('invoices')
                ->selectRaw('sum(discount) as totalDiscount')
                ->whereRaw("date_trunc('day',invoices.created_at) = CURRENT_DATE")
                ->get();

        $daily_sales = number_format(($daily->sum('totalamount') - $daily_discount->sum('totaldiscount')),2);

        //$daily_sales = $daily->sum('totalamount');

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

        $weekly_sales = number_format(($weekly->sum('totalamount') - $weekly_discount->sum('totaldiscount')), 2);


        $monthly = DB::table('invoice_products as inv_p')
                ->selectRaw("sum(total) as totalAmount, date_trunc('month',created_at) as monthly_sales")
                ->groupBy('monthly_sales')
                ->get();

        $monthly_discount = DB::table('invoices')
                ->selectRaw("sum(discount) as totalDiscount, date_trunc('month',created_at) as monthly_discount")
                ->groupBy('monthly_discount')
                ->get();

        $monthly_sales = number_format(($monthly->sum('totalamount') - $monthly_discount->sum('totaldiscount')), 2);

        $yearly = DB::table('invoice_products as inv_p')
                ->selectRaw("sum(total) as totalAmount, date_trunc('year',created_at) as yearly_sales")
                ->groupBy('yearly_sales')
                ->get();

        $yearly_discount = DB::table('invoices')
                ->selectRaw("sum(discount) as totalDiscount, date_trunc('year',created_at) as yearly_discount")
                ->groupBy('yearly_discount')
                ->get();

        $yearly_sales = number_format(($yearly->sum('totalamount') - $yearly_discount->sum('totaldiscount')), 2);


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
