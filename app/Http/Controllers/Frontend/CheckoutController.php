<?php

namespace App\Http\Controllers\Frontend;


use App\Models\CustomerAddress;
use App\Models\ShippingRate;
use App\Models\Discount;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use Carbon\Carbon;

class CheckoutController extends Controller
{
	
	public function __construct()
	{
		$this->middleware('auth:customer');
	}

	public function index(Request $request)
	{

        $customer_id = Auth::guard('customer')->user()->id;

        $cart_products = Cart::where('customer_id', $customer_id)->get();

        if (count($cart_products) == 0)
        {
            return redirect(url('/products'));
        }

        $customer = Customer::where('id', $customer_id)->with('addresses')->first();

        $cart = Cart::where('customer_id', $customer_id)->with('inventory.product')->get();;

        $cart_qty = Cart::where('customer_id', $customer_id)->sum('quantity');

        $cart_subtotal = Cart::where('customer_id', $customer_id)->sum('total');


		$data = 'Checkout';

        $shipping_rate = ShippingRate::first()->flat_rate;

        $discount = Discount::first();

		return view('frontend.checkout', compact(
                'data',
                'customer',
                'discount',
                'cart',
                'shipping_rate'
            ));
	}

    public function orderReceived()
    {
        if (Session::has('received_order'))
        {
            $data = 'Order Success';
            $redirect = view('frontend.customer.order_received', compact('data'));
        }
        else
        {
            $redirect = redirect()->route('frontend_homepage');
        }
        return $redirect;
    }

    public function checkoutFailed()
    {
        if (Session::has('checkout_failed'))
        {
            $data = 'Checkout Failed';
            $redirect = view('frontend.customer.checkout_failed', compact('data'));
        }
        else
        {
            $redirect = redirect()->route('frontend_homepage');
        }

        return $redirect;
    }

   
}