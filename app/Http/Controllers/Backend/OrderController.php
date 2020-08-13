<?php

namespace App\Http\Controllers\Backend;

use App\PaypalPayment;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PayPal\Api\Amount;
use PayPal\Api\RefundRequest;
use PayPal\Api\Sale;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Exception\PayPalConnectionException;
use Config;
use Exception;

class OrderController extends Controller
{
    private $_api_context;


    public function __construct()
    {

        $paypal_conf = Config::get('paypal');

        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret']
            
        ));

        $this->_api_context->setConfig($paypal_conf['settings']);

    	$this->middleware('auth:admin');
    }

    public function index()
    {
        $data = 'Orders';

        return view('backend.orders.order_list', compact('data'));
    }
    
    public function forPickup()
    {
        return view('backend.orders.for_pickup');
    }

    public function pendingPayment()
    {
        return view('backend.orders.pending_payment');
    }

    public function processing()
    {
        return view('backend.orders.processing');
    }

    public function delivered()
    {
        return view('backend.orders.delivered');
    }

    public function completed()
    {
        return view('backend.orders.completed');
    }

    public function viewOrder($order)
    {   
        $previous_url = url()->previous();

        $orderViewed = Order::where('number', $order)->first();
        $orderViewed->viewed = 1;
        $orderViewed->update();

        $orderData = Order::where('number','=',$order)
            ->with(
                'bankDepositSlip',
                'delivery'
            )
            ->first();
        
    	return view('backend.orders.order_details')->with([
            'order' => $orderData,
            'previous_url'=>$previous_url,
            'order_num'=>$order
        ]);
    } 
}
