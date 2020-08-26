<?php

namespace App\Http\Controllers\Frontend;


use App\Models\Address;
use App\Models\Province;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\ShippingRate;
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

        $shippingRate = ShippingRate::first();

        $cart_subtotal = Cart::where('customer_id', $customer_id)->sum('total');

        $discount_percent = $shippingRate->discount_percentage;

        if ((int)$cart_qty >= $shippingRate->order_quantity)
        {
            $has_discount = true;

            $manila_rate_discount = ($shippingRate->manila_rate * $discount_percent / 100);
            $manila_rate = $shippingRate->manila_rate;
            $manila_rate_discounted = $shippingRate->manila_rate - $manila_rate_discount;

            $province_rate_discount = ($shippingRate->province_rate * $discount_percent / 100);
            $province_rate = $shippingRate->province_rate;
            $province_rate_discounted = $shippingRate->province_rate - $province_rate_discount;

        }
        else
        {
            $discount = 0;
            $has_discount = false;
            $province_rate_discount = 0;
            $manila_rate_discount = 0;
            $manila_rate = $shippingRate->manila_rate;
            $province_rate = $shippingRate->province_rate;
            $province_rate_discounted = $shippingRate->province_rate;
            $manila_rate_discounted = $shippingRate->manila_rate;
        }


        $shipping_rate = array(
                    'has_discount' => $has_discount,
                    'manila_rate' => number_format($manila_rate,2),
                    'province_rate' => number_format($province_rate,2),
                    'province_rate_discount'=> number_format($province_rate_discount,2),
                    'manila_rate_discount'=> number_format($manila_rate_discount,2),
                    'province_rate_discounted'=> number_format($province_rate_discounted,2),
                    'manila_rate_discounted'=> number_format($manila_rate_discounted,2)
                );

		$data = 'Checkout';
            
        $provinces = Province::all();

        $municipalities = Municipality::all();

        $barangays = Barangay::all();
        
        

		return view('frontend.checkout', compact(
                'data',
                'customer',
                'cart',
                'provinces',
                'municipalities',
                'barangays',
                'shipping_rate',
                'cart_subtotal',
                'address'
            ));
	}

    public function orderReceived()
    {
        if (Session::has('received_order'))
        {
            $redirect = view('frontend.customer.order_received');
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
            $redirect = view('frontend.customer.checkout_failed');
        
        }
        else
        {
            $redirect = redirect()->route('frontend_homepage');
        }

        return $redirect;
    }

   
}