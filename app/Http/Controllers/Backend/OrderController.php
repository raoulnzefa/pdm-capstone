<?php

namespace App\Http\Controllers\Backend;

use App\Models\CompanyDetails;
use App\Models\PaypalPayment;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Http\Controllers\Traits\InventoryManager;
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
use DB;

class OrderController extends Controller
{
    use InventoryManager;

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
        $company = CompanyDetails::first();

        return view('backend.orders.order_list', compact('data','company'));
    }
    
    public function viewOrder($order)
    {   
        $previous_url = url()->previous();
        $data = 'Order details';
        $orderViewed = Order::where('number', $order)->first();
        $orderViewed->viewed = 1;
        $orderViewed->update();

        $orderData = Order::where('number','=',$order)
            ->with(
                'bankDepositSlip',
                'shipping'
            )
            ->first();

        
    	return view('backend.orders.order_details')->with([
            'order' => $orderData,
            'previous_url'=>$previous_url,
            'order_num'=>$order,
            'data' => $data,
            'company' => CompanyDetails::first()
        ]);
    } 
}
