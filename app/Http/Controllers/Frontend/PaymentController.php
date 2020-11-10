<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CompanyDetails;
use Illuminate\Support\Facades\Input;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
/** All Paypal Details class **/
use PayPal\Api\ItemList;
use PayPal\Api\InputFields;
use PayPal\Api\WebProfile;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Exception\PayPalConnectionException;
use Redirect;
use Session;
use URL;
use Config;
use Exception;
use Auth;
use Carbon\Carbon;
use App\Notifications\OrderConfirmation;
use App\Notifications\BankDepositEmail;
use App\Notifications\PickupEmail;
use App\Http\Controllers\Traits\InventoryManager;
use App\Http\Controllers\Traits\InvoiceTraits;
use App\Http\Controllers\Traits\AddressTraits;
use App\Http\Controllers\Traits\OrderTraits;
use App\Http\Controllers\Traits\ShippingAddressTraits;
use App\Http\Controllers\Traits\StorePickupTraits;
use App\Models\PaypalPayment;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\ShippingAddress;
use App\Models\StorePickup;
use App\Models\BankAccount;
use App\Models\BankDepositPayment;
use App\Models\Inventory;
use App\Models\ProductVariant;
use App\Models\CustomerAddress;
use App\Models\ShippingRate;
use App\Models\Discount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
	use InventoryManager, AddressTraits, OrderTraits, StorePickupTraits, ShippingAddressTraits, InvoiceTraits;

	private $_api_context;

	public function __construct()
	{
		$paypal_conf = Config::get('paypal');

		$this->_api_context = new ApiContext(new OAuthTokenCredential(
			$paypal_conf['client_id'],
			$paypal_conf['secret']

		));

		$this->_api_context->setConfig($paypal_conf['settings']);

		$this->middleware('auth:customer');
	}

	public function checkoutSubmit(Request $request)
	{
		//dd($request->all());

		$request->validate([
			'shipping_method' => 'required',
			'payment_method' => 'required',
			'pickup_firstname' => 'sometimes|required',
			'pickup_lastname' => 'sometimes|required',
			'pickup_mobile_no' => 'sometimes|required',
			'first_name' => 'sometimes|required',
			'last_name' => 'sometimes|required',
			'street' => 'sometimes|required',
			'barangay' => 'sometimes|required',
			'municipality' => 'sometimes|required',
			'province' => 'sometimes|required',
			'zip_code' => 'sometimes|required',
			'mobile_no' => 'sometimes|required',
			'shipping_fee' => 'sometimes|required'
		]);

    	//dd((bool)$request->ship_to_diff_addr);

    	// set time zone
		date_default_timezone_set("Asia/Manila");

		$company = CompanyDetails::first();

		$subtotal = str_replace(',', '', $request->cart_subtotal);

		$discount = Discount::where('is_disabled', 0)->first();


		$customer = Customer::where('id', (int)$request->customer_id)->first();

		$cart_products = Cart::where('customer_id', (int)$request->customer_id)->get();

		$order_discount = 0;

		$has_discount = false;

		if (!is_null($discount))
		{
			if ((int)$request->order_qty >= (int)$discount->order_quantity)
			{
				$has_discount = true;
				$order_discount = (float)$subtotal * ($discount->discount_percent / 100);
			}
			else
			{
				$has_discount = false;
				$order_discount = 0;
			} 
		}

		if ($request->shipping_method == 'store_pickup')
		{
			try
			{
				$shipping_method = str_replace('_', ' ', $request->shipping_method);

				$reserved_days = (int)$company->reserved_days;
        //db format
				$reserved_until = strftime("%Y-%m-%d", strtotime("+$reserved_days days"));

        // Cash payment only
				$order_params = array(
					'customer_id' => (int)$request->customer_id,
					'order_status' => 'For pickup',
					'order_shipping_method' => ucwords($shipping_method),
					'order_payment_status' => 'Pending',
					'order_payment_method' => ucfirst($request->payment_method),
					'order_quantity' => (int)$request->order_qty,
					'order_shipping_fee' => 0,
					'order_subtotal' => (float)$subtotal,
					'order_discount' => $order_discount,
					'order_total' => (float)$request->order_total,
					'order_shipping_discount' => 0,
					'order_due_payment' => NULL,
					'order_for_shipping' => NULL,
					'order_for_pickup' => $reserved_until,
					'order_follow_up_email' => NULL,
					'order_paypal_url' => NULL,
					'order_payment_date' => NULL,
					'order_reserved_days' => $reserved_days,
					'order_processing_days' => NULL,
					'order_due_payment_days' => NULL,
					'cart_products' => $cart_products
				);

				$order_number = $this->createOrder($order_params);

				$store_pickup_params = array(
					'order_number' => $order_number,
					'pickup_firstname' => ucfirst($request->pickup_firstname),
					'pickup_lastname' => ucfirst($request->pickup_lastname),
				);

				$this->createStorePickup($store_pickup_params);

				$this->updateInventory($order_number);

				$due_date_email = strftime("%A, %B %d, %Y", strtotime("+$reserved_days days"));

				$orderData = Order::where('number', $order_number)->first();

				$dateData = ['date'=>$due_date_email,'days'=> $reserved_days];



      // send email

				$customer->notify(new PickupEmail($orderData, $dateData));
			}
			catch(Exception $ex)
			{
				dd('Store pickup: '.$ex->getMessage());
        // redirect to checkout error page
        //return redirect()->route('checkout.failed')->with('checkout_failed', true);
			}
      // redirect to success order page
      //Delete cart details
			Cart::where('customer_id', (int)$request->customer_id)->delete();

			return redirect()->route('order.received')->with([
				'order'=> $orderData,
				'received_order'=>true
			]);
		}
		elseif ($request->shipping_method == 'shipping')
		{ 

			$province = str_replace(' (Not a Province)', ' ', $request->province);
			$municipality = $request->municipality;
			$barangay = $request->barangay;

			$shipping_method = str_replace('_', ' ', $request->shipping_method);

			if ((bool)$request->ship_to_diff_addr)
			{
              //save new address
				$address_params = array(
					'customer_id' => $customer->id,
					'firstname' => $request->first_name,
					'lastname' => $request->last_name,
					'street' => ucfirst($request->street),
					'barangay' => $barangay,
					'municipality' => $municipality,
					'province' => $province,
					'barangay_id' => $request->barangay_id,
					'municipality_id' => $request->municipality_id,
					'province_id' => $request->province_id,
					'zip_code' => $request->zip_code,
					'mobile_no' => $request->mobile_no
				);

				$this->createAddress($address_params);
			}


			if ($request->payment_method == 'Bank Deposit')
			{
				try
				{
					$due_payment_days = (int)$company->due_payment_days;

					$estimated_date = strftime("%Y-%m-%d", strtotime("+$due_payment_days days"));

					$due_date = strftime("%Y-%m-%d %T", strtotime("+$due_payment_days days"));
					
					$follow_up_days = (int)$company->follow_up_days;

					$follow_up_date = strftime("%Y-%m-%d", strtotime("+$follow_up_days days"));

					$order_params = array(
						'customer_id' => (int)$request->customer_id,
						'order_status' => 'Pending payment',
						'order_shipping_method' => ucwords($shipping_method),
						'order_payment_status' => 'Pending',
						'order_payment_method' => ucfirst($request->payment_method),
						'order_quantity' => (int)$request->order_qty,
						'order_subtotal' => (float)$subtotal,
						'order_discount' => $order_discount,
						'order_shipping_fee' => (float)$request->shipping_fee,
						'order_total' => (float)$request->order_total,
						'order_shipping_discount' => 0,
						'order_for_shipping' => NULL,
						'order_due_payment' => $due_date,
						'order_follow_up_email' => $follow_up_date,
						'order_for_pickup' => NULL,
						'order_paypal_url' => NULL,
						'order_payment_date' => NULL,
						'order_reserved_days' => NULL,
						'order_processing_days' => NULL,
						'order_due_payment_days' => $due_payment_days,
						'cart_products' => $cart_products
					);

					$order_number = $this->createOrder($order_params);

					$this->updateInventory($order_number);

					$due_date_email = strftime("%A, %B %d, %Y", strtotime("+$due_payment_days days"));

					$shipping_params = array(
						'order_number' => $order_number,
						'shipping_firstname' => $request->first_name,
						'shipping_lastname' => $request->last_name,
						'shipping_street' => ucfirst($request->street),
						'shipping_barangay' => $barangay,
						'shipping_municipality' => $municipality,
						'shipping_province' => $province,
						'shipping_zip_code' => $request->zip_code,
						'shipping_mobile_no' => $request->mobile_no
					);

					$this->createShipping($shipping_params);

					$orderData = Order::where('number', $order_number)->first();

               //Send Confirmation Email for Bank Deposit
               //Containing Bank Account Detail

					$dateData = ['date'=>$due_date_email,'days'=> $due_payment_days];

               // get bank account
					$bank_account = BankAccount::where('active', 1)->first();
               //dd($bank_account);
               // save bank deposit payment
					$bank_deposit_payment = new BankDepositPayment;
					$bank_deposit_payment->bank_account_id = $bank_account->id;
					$bank_deposit_payment->order_number = $order_number;
					$bank_deposit_payment->save();

               // send email
					$customer->notify(new BankDepositEmail($orderData, $dateData, $bank_account));
				}
				catch(Exception $ex)
				{
					dd('Bank deposit: '.$ex->getMessage());
               // redirect to checkout error page
               //return redirect()->route('checkout.failed')->with('checkout_failed', true);  
				}

             //Delete cart details
				Cart::where('customer_id', (int)$request->customer_id)->delete();

				return redirect()->route('order.received')->with([
					'order'=> $orderData,
					'received_order'=>true
				]);   

			}
			elseif ($request->payment_method == 'PayPal')
			{  
            //dd((float)$request->shipping_fee + (float)$subtotal);
				$payer = new Payer();
				$payer->setPaymentMethod("paypal");

            // Set redirect URLs
				$redirectUrls = new RedirectUrls();
				$redirectUrls->setReturnUrl(url('status'))
				->setCancelUrl(url('status'));

				$i = 0;

				foreach ($cart_products as $cart) {

					$item[$i] = new Item();
					$item[$i]->setName($cart['product_name']);
					$item[$i]->setCurrency("PHP");
					$item[$i]->setQuantity((int)$cart['quantity']);
					$item[$i]->setPrice(str_replace(',', '', (float)$cart['price']));
					$i++;

				}

				if ($has_discount)
				{
					$discount_amount = new Item();
					$discount_amount->setName('Discount')
					->setCurrency('PHP')
					->setQuantity(1)
					->setPrice('-'.$order_discount);

					array_push($item, $discount_amount);
				}

				$item_list = new ItemList();
				$item_list->setItems($item);

				$details = new Details();
				$details->setSubtotal(($has_discount) ? (float)$request->subtotal_with_discount : (float)$subtotal)
				->setShipping((float)$request->shipping_fee);


             // Set payment amount
				$amount = new Amount();
				$amount->setCurrency("PHP")
				->setTotal((float)$request->order_total)
				->setDetails($details);


            // Set transaction object
				$transaction = new Transaction();
				$transaction->setAmount($amount)
				->setItemList($item_list)
				->setDescription("Payment description");



				$inputFields = new InputFields();
				$inputFields->setAllowNote(true)
                ->setNoShipping(1) // Important step
                ->setAddressOverride(0);

                $webProfile = new WebProfile();
                $webProfile->setName(uniqid())
                ->setInputFields($inputFields)
                ->setTemporary(true);

                $createProfile = $webProfile->create($this->_api_context);


            // Create the full payment object
                $payment = new Payment();
                $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions(array($transaction))
                ->setExperienceProfileId($createProfile->getId()); ;

            //dd($transaction);

                try
                {
                	$payment->create($this->_api_context);

              // Get PayPal redirect URL and redirect the customer
              //$approvalUrl = $payment->getApprovalLink();

              // Redirect the customer to $approvalUrl
                }
                catch (PayPalConnectionException $e)
                {

                	dd('Paypal Exception 1: '.$e->getData());
                	exit(1); 
                //Cart::where('customer_id', $request->customer_id)->delete();
                // delete failed 
                //Order::where('number', $updateOrder->number)->delete();
                	Session::put('checkout_failed', 'Checkout Failed');

                	return redirect()->route('checkout.failed');

                }
                catch (Exception $ex)
                {
                	dd('Paypal Exception 2: '.$ex->getMessage());
                	Session::put('checkout_failed', 'Checkout Failed');

                	return redirect()->route('checkout.failed');
                }


                foreach ($payment->getLinks() as $link) 
                {

                	if ($link->getRel() == 'approval_url')
                	{
                		$redirect_url = $link->getHref();
                		break;
                	}  
                }

                Session::put('paypal_redirect_url', $redirect_url);
                Session::put('paypal_payment_id', $payment->getId());

                if (isset($redirect_url))
                {

                	Session::put('chk_cust_id', (int)$request->customer_id);
                	Session::put('chk_first_name', $request->first_name);
                	Session::put('chk_last_name', $request->last_name);
                	Session::put('chk_street', $request->street);
                	Session::put('chk_province', $province);
                	Session::put('chk_municipality', $municipality);
                	Session::put('chk_barangay', $barangay);
                	Session::put('chk_zip_code', $request->zip_code);
                	Session::put('chk_mobile_no', $request->mobile_no);
                	Session::put('chk_shipping_method', ucfirst($request->shipping_method));
                	Session::put('chk_payment_method', ucfirst($request->payment_method));
                	Session::put('chk_quantity', (int)$request->order_qty);
                	Session::put('chk_subtotal', (float)$subtotal);
                	Session::put('chk_discount', (float)$order_discount);
                	Session::put('chk_shipping_fee', (float)$request->shipping_fee);
                	Session::put('chk_total', (float)$request->order_total);
                	Session::put('chk_cart_products', $cart_products);

                	return Redirect::away($redirect_url);
                }

            // Session::put('error', 'Unknown error occured');

            // return Redirect::to('/payment/failed');
                Session::put('checkout_failed', 'Checkout Failed');

                return redirect()->route('checkout.failed');
             }
          }
       }

       public function getPaymentStatus()
       {
       	/** Get the payment ID before session clear **/
       	$payment_id = Session::get('paypal_payment_id');

       	/** clear the session payment ID **/
        //Session::forget('paypal_payment_id');

       	if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {

            // Session::put('error', 'Payment failed');
            // return Redirect::to('/');
       		Session::put('checkout_failed', 'Checkout Failed');

       		return redirect()->route('checkout.failed');
       	}

       	$payment = Payment::get($payment_id, $this->_api_context);

       	$execution = new PaymentExecution();
       	$execution->setPayerId(Input::get('PayerID'));
       	/**Execute the payment **/
       	$result = $payment->execute($execution, $this->_api_context);

  

       	if ($result->getState() == 'approved')
       	{

       		try
       		{
       			$customer_id = Session::get('chk_cust_id');

       			$transaction = $result->getTransactions();

       			$subtotal = $transaction[0]->getAmount()->getDetails()->getSubtotal();
       			$shipping = $transaction[0]->getAmount()->getDetails()->getShipping();

       			$payer = $result->getPayer();

       			$paymentMethod = $payer->getPaymentMethod();
       			$payerStatus = $payer->getStatus();
       			$payerMail = $payer->getPayerInfo()->getEmail();

       			$relatedResources = $transaction[0]->getRelatedResources();
       			$sale = $relatedResources[0]->getSale();

       			$saleId = $sale->getId();
       			$createdTime = $sale->getCreateTime();
       			$updatedTime = $sale->getUpdateTime();

       			$state = $sale->getState();
       			$total = $sale->getAmount()->getTotal();

       			date_default_timezone_set("Asia/Manila");


       			if (Session::get('chk_shipping_method') == 'Shipping')
       			{
       				$order_status = 'Processing';
       				$reserved_until = NULL;

       			}

       			$company = CompanyDetails::first();
            	//delivery working days....
       			$delivery_days = (int)$company->delivery_days;
            	//db format
       			$estimated_date = strftime("%Y-%m-%d", strtotime("+$delivery_days days"));          

       			$payment_status = 'Paid';

       			$dt = Carbon::now();

       			$date_now = $dt->toDateString();

       			$emailDate = strftime("%A, %B %d, %Y", strtotime("+$delivery_days days"));

       			$dateData = ['date'=>$emailDate, 'days'=> $delivery_days];

       			$order_params = array(
       				'customer_id' => (int)$customer_id,
       				'order_status' => $order_status,
       				'order_shipping_method' => Session::get('chk_shipping_method'),
       				'order_payment_status' => $payment_status,
       				'order_payment_method' => Session::get('chk_payment_method'),
       				'order_quantity' => Session::get('chk_quantity'),
       				'order_subtotal' => Session::get('chk_subtotal'),
       				'order_shipping_fee' => Session::get('chk_shipping_fee'),
       				'order_total' => Session::get('chk_total'),
       				'order_discount' => Session::get('chk_discount'),
       				'order_shipping_discount' => NULL,
       				'order_for_shipping' => $estimated_date,
       				'order_follow_up_email' => NULL,
       				'order_due_payment' => NULL,
       				'order_for_pickup' => NULL,
       				'order_reserved_days' => NULL,
						'order_processing_days' => $delivery_days,
       				'order_paypal_url' => Session::get('paypal_redirect_url'),
       				'order_payment_date' => date("Y-m-d H:i:s"),
       				'order_reserved_days' => $delivery_days,
						'order_processing_days' => NULL,
						'order_due_payment_days' => NULL,
       				'cart_products' => Session::get('chk_cart_products')
       			);

       			$order_number = $this->createOrder($order_params);

       			$this->updateInventory($order_number);

       			$shipping_params = array(
       				'order_number' => $order_number,
       				'shipping_firstname' => ucfirst(Session::get('chk_first_name')),
       				'shipping_lastname' => ucfirst(Session::get('chk_last_name')),
       				'shipping_street' => ucfirst(Session::get('chk_street')),
       				'shipping_barangay' => Session::get('chk_barangay'),
       				'shipping_municipality' => Session::get('chk_municipality'),
       				'shipping_province' => Session::get('chk_province'),
       				'shipping_zip_code' => Session::get('chk_zip_code'),
       				'shipping_mobile_no' => Session::get('chk_mobile_no')
       			);

       			$this->createShipping($shipping_params);

       			$paypal = new PaypalPayment;
       			$paypal->order_number = $order_number;
       			$paypal->transaction_id = $saleId;
       			$paypal->payment_method = $paymentMethod;
       			$paypal->payer_status = $payerStatus;
       			$paypal->payer_email = $payerMail;
       			$paypal->subtotal = $subtotal;
       			$paypal->shipping = $shipping;
       			$paypal->total = $total;
       			$paypal->payer_state = $state;
       			$paypal->created = $createdTime;
       			$paypal->save();

       			$orderData = Order::where('number', $order_number)->first();

       			$invoice_params = ['order'=> $orderData];

       			$this->createInvoice($invoice_params);

       			$dateData = ['date'=>$emailDate, 'days'=> $delivery_days];

       			$customer = Customer::where('id',$customer_id)->first();
            // send order confirmation
       			$customer->notify(new OrderConfirmation($orderData, $dateData));

            // Delete cart
       			Cart::where('customer_id', $customer_id)->delete();

       			Session::forget('chk_cust_id');
       			Session::forget('chk_first_name');
       			Session::forget('chk_last_name');
       			Session::forget('chk_street');
       			Session::forget('chk_province');
       			Session::forget('chk_municipality');
       			Session::forget('chk_barangay');
       			Session::forget('chk_zip_code');
       			Session::forget('chk_mobile_no');
       			Session::forget('chk_shipping_method');
       			Session::forget('chk_payment_method');
       			Session::forget('chk_quantity');
       			Session::forget('chk_subtotal');
       			Session::forget('chk_discount');
       			Session::forget('chk_shipping_fee');
       			Session::forget('chk_total');
       			Session::forget('chk_cart_products');
       			Session::forget('paypal_payment_id');
       			Session::forget('paypal_redirect_url');

       			return redirect()->route('order.received')->with([
       				'order'=> $orderData,
       				'received_order'=>true
       			]);

       		}
       		catch(Exception $ex)
       		{
       			dd($ex->getMessage());
       		}

        }// endif

     }
  }
