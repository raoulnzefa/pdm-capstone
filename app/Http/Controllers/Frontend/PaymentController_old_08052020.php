<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Traits\SalesTransaction;
use App\Http\Controllers\Traits\InventoryManager;
use App\BankAccount;
use App\BankDepositPayment;
use App\Province;
use App\Municipality;
use App\Barangay;
use App\Inventory;
use App\Notifications\PickupEmail;
use App\ProductVariant;
use App\Notifications\BankDepositEmail;
use App\PaypalPayment;
use App\Notifications\OrderConfirmation;
use App\Cart;
use App\Customer;
use App\Order;
use App\OrderProduct;
use App\Invoice;
use App\InvoiceProduct;
use App\Product;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
/** All Paypal Details class **/
use PayPal\Api\ItemList;
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

class PaymentController extends Controller
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

        $this->middleware('auth:customer');
    }

    public function testSubmit(Request $request)
    {
        return dd($request->all());
    }

    public function checkoutSubmit(Request $request)
    {	

        $customer_id = $request->customer_id;
        $first_name = ucwords($request->first_name);
        $last_name = ucwords($request->last_name);
        $street = $request->street;
        $province = $request->province_name;
        $municipality = $request->municipality_name;
        $barangay = $request->barangay_name;
        $zip_code = $request->zip_code;
        $company = $request->company;
        $phone_number = $request->mobile_no;
        $shipping_method = $request->shipping_method;
        $payment_method = $request->payment_method;
        $cart_qty = $request->cart_qty; 
        $subtotal = $request->cart_subtotal;
        $discount = 0;
        $shipping_fee = $request->shipping_amount;
        $total = $request->total_order;

        $cart_products = Cart::where('customer_id', $request->customer_id)->get();
        
        
        $customer = Customer::where('id', $request->customer_id)->first();

        // set time zone
        date_default_timezone_set("Asia/Manila");

        //2 working days payment and pickup due date....
        $d = 2;
        
        //db format
        $due_date = strftime("%Y-%m-%d", strtotime("+$d weekday"));

        $status = '';
        
        $payment_status = '';

        $reserved_until = NULL;

        //Delete cart details
        //Cart::where('customer_id', $request->customer_id)->delete(); 

        if ($payment_method === 'PayPal')
        {
            //dd($cart_total);
            
            $payer = new Payer();
            $payer->setPaymentMethod("paypal");

            // Set redirect URLs
            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl(url('status'))
              ->setCancelUrl(url('status'));

            $i = 0;

            foreach ($cart_products as $cart) {
                
                $item[$i] = new Item();

                if ($cart['product']['has_variant'] == 'Yes')
                {
                    $prodVar = ProductVariant::where('id', $cart['carting']['number'])->first();
                    $name = $cart['product']['name'].' '.$cart['carting']['variant'];
                    $item[$i]->setName($name);
                }
                else
                {
                    $item[$i]->setName($cart['product']['name']);
                }

                $item[$i]->setCurrency("PHP");
                $item[$i]->setQuantity($cart['quantity']);
                $item[$i]->setPrice(str_replace(',', '', $cart['price']));
                $i++;

            }

            $discount_amount = new Item();
            $discount_amount->setName('Discount')
                ->setCurrency('PHP')
                ->setQuantity(1)
                ->setPrice('-'.$discount);

            array_push($item, $discount_amount);

            $item_list = new ItemList();
            $item_list->setItems($item);

            $details = new Details();
            $details->setSubtotal($subtotal)
                ->setShipping($shipping_fee);

            
             // Set payment amount
            $amount = new Amount();
            $amount->setCurrency("PHP")
              ->setTotal($total)
              ->setDetails($details);


            // Set transaction object
            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription("Payment description");
           
           //dd($transaction);

            // Create the full payment object
            $payment = new Payment();
            $payment->setIntent('sale')
              ->setPayer($payer)
              ->setRedirectUrls($redirectUrls)
              ->setTransactions(array($transaction));

            //dd($transaction);

            try {
              $payment->create($this->_api_context);

              // Get PayPal redirect URL and redirect the customer
              //$approvalUrl = $payment->getApprovalLink();

              // Redirect the customer to $approvalUrl
            } catch (PayPalConnectionException $e) {

                echo $e->getData();
                exit(1); 
                //Cart::where('customer_id', $request->customer_id)->delete();
                // delete failed 
                //Order::where('number', $updateOrder->number)->delete();
                Session::put('checkout_failed', 'Checkout Failed');

                return redirect()->route('checkout.failed');
               


            } catch (Exception $ex) {
                Session::put('checkout_failed', 'Checkout Failed');

                return redirect()->route('checkout.failed');
            }
            
            //Delete cart details
            //Cart::where('customer_id', $request->customer_id)->delete();


            foreach ($payment->getLinks() as $link) {
                
                if ($link->getRel() == 'approval_url')
                {
                    $redirect_url = $link->getHref();
                    break;
                }  
            }

            // save paypal redirect url just in case encounter problem when paying through paypal

            Session::put('paypal_redirect_url', $redirect_url);
            Session::put('paypal_payment_id', $payment->getId());

            if (isset($redirect_url))
            {
                Session::put('chk_cust_id', $customer_id);
                Session::put('chk_first_name', $first_name);
                Session::put('chk_last_name', $last_name);
                Session::put('chk_street', $street);
                Session::put('chk_province', $province);
                Session::put('chk_municipal', $municipality);
                Session::put('chk_barangay', $barangay);
                Session::put('chk_zip_code', $zip_code);
                Session::put('chk_company', $company);
                Session::put('chk_phone_number', $phone_number);
                Session::put('chk_shipping_method', $shipping_method);
                Session::put('chk_payment_method', $payment_method);
                Session::put('chk_quantity', $cart_qty);
                Session::put('chk_subtotal', $subtotal);
                Session::put('chk_discount', $discount);
                Session::put('chk_shipping_cost', $shipping_fee);
                Session::put('chk_total', $total);
                Session::put('chk_cart_products', $cart_products);

                return Redirect::away($redirect_url);
            }

            Session::put('error', 'Unknown error occured');

            return Redirect::to('/payment/failed');

        }
        elseif ($payment_method == 'Bank Deposit')
        {
            if ($shipping_method == 'Shipping')
            {
                //delivery working days....
                $d_delivery = 3;
                //db format
                $estimated_date = strftime("%Y-%m-%d", strtotime("+$d_delivery weekday"));
                $shipping_date = strftime("%Y-%m-%d", strtotime("+$d_delivery weekday"));

                // Save order details
                $status = 'Reserved';

                $payment_status = 'Pending';
            
                $array_params = array(
                    'customer_id' => $customer_id,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'street' => $street,
                    'province' => $province,
                    'municipal' => $municipality,
                    'barangay' => $barangay,
                    'zip_code' => $zip_code,
                    'company' => $company,
                    'phone_number' => $phone_number,
                    'shipping_method' => $shipping_method,
                    'payment_method' => $payment_method,
                    'quantity' => $cart_qty,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'shipping_cost' => $shipping_fee,
                    'reserved_until' => $reserved_until,
                    'shipping_date'=> $shipping_date,
                    'total' => $total,
                    'status' => $status,
                    'date_paid' => NULL,
                    'payment_status' => $payment_status,
                    'paypal_redirect_url' => NULL,
                    'cart_products' => $cart_products
                );
               
                // Save order
                $order_number = $this->saveOrder($array_params);

                $due_date_email = strftime("%A, %B %d, %Y", strtotime("+$d_delivery weekday"));

                //Send Confirmation Email for Bank Deposit
                //Containing Bank Account Details
                $orderData = Order::where('number', $order_number)->first();

                $date_data = ['date'=>$due_date_email,'days'=> $d_delivery];

                try
                {
                    // get bank account
                    $bank_account = BankAccount::where('active', 1)->first();
                    //dd($bank_account);
                    // save bank deposit payment
                    $bank_deposit_payment = new BankDepositPayment;
                    $bank_deposit_payment->bank_account_id = $bank_account->id;
                    $bank_deposit_payment->order_number = $order_number;
                    $bank_deposit_payment->save();

                    // send email
                    $customer->notify(new BankDepositEmail($orderData, $date_data, $bank_account));    
                }
                catch(Exception $e)
                {
                    //Order::where('id', $order->id)->delete();
                    return 'payment bank deposit:'. $e->getMessage();
                    //return redirect()->back()->with('paypal_error', 'There was an error encountered.');   
                }

            }
        }
        else 
        {
            // Pick up
            $reserved_until = $due_date;  

            // Save order details
            $status = 'Reserved';

            $payment_status = 'Pending';
                
            $shipping_date = NULL;

            $array_params = array(
                'customer_id' => $customer_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'street' => $street,
                'province' => $province,
                'municipal' => $municipality,
                'barangay' => $barangay,
                'zip_code' => $zip_code,
                'company' => $company,
                'phone_number' => $phone_number,
                'shipping_method' => $shipping_method,
                'payment_method' => $payment_method,
                'quantity' => $cart_qty,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping_cost' => $shipping_fee,
                'reserved_until' => $reserved_until,
                'shipping_date' => $shipping_date,
                'total' => $total,
                'status' => $status,
                'date_paid' => NULL,
                'payment_status' => $payment_status,
                'paypal_redirect_url' => NULL,
                'cart_products' => $cart_products
            );
           
            // Save order
            $order_number = $this->saveOrder($array_params);

            $this->updateInventory($order_number);

            $due_date_email = strftime("%A, %B %d, %Y", strtotime("+$d weekday"));

            $orderData = Order::where('number', $order_number)->first();

            $date_data = ['date'=>$due_date_email,'days'=> $d];

            try
            {
                $customer->notify(new PickupEmail($orderData, $date_data));
            }
            catch(Exception $e)
            {
                return 'payment cash:'. $e->getMessage();
                //return redirect()->back()->with('paypal_error', 'There was an error encountered.');  
            }
        }

        //Delete cart details
        Cart::where('customer_id', $customer_id)->delete();

        return redirect()->route('order.received')->with([
            'order'=> $orderData,
            'received_order'=>true
        ]);
  
    }

    public function getPaymentStatus()
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');

        /** clear the session payment ID **/
        //Session::forget('paypal_payment_id');

        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {

            Session::put('error', 'Payment failed');
            return Redirect::to('/');
        }

        $payment = Payment::get($payment_id, $this->_api_context);

        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved')
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

            //Session::put('success', 'Payment success');
            date_default_timezone_set("Asia/Manila");

            $d_reserved = 2;

            $due_date = strftime("%Y-%m-%d", strtotime("+$d_reserved weekday"));
            //delivery working days....
            $d_delivery = 3;
            //db format
            $estimated_date = strftime("%Y-%m-%d", strtotime("+$d_delivery weekday"));


            if (Session::get('chk_shipping_method') == 'Shipping')
            {
                $status = 'Processing';
                $reserved_until = NULL;
            }
            else
            {
                // Pickup
                $status = 'Reserved';
                $reserved_until = $due_date;
            }

            $shipping_date = strftime("%Y-%m-%d", strtotime("+$d_delivery weekday"));

            $payment_status = 'Paid';

            $dt = Carbon::now();
            
            $date_now = $dt->toDateString();

            $order_params = array(
                'customer_id' => Session::get('chk_cust_id'),
                'first_name' => Session::get('chk_first_name'),
                'last_name' => Session::get('chk_last_name'),
                'street' => Session::get('chk_street'),
                'province' => Session::get('chk_province'),
                'municipal' => Session::get('chk_municipal'),
                'barangay' => Session::get('chk_barangay'),
                'zip_code' => Session::get('chk_zip_code'),
                'company' => Session::get('chk_company'),
                'phone_number' => Session::get('chk_phone_number'),
                'shipping_method' => Session::get('chk_shipping_method'),
                'payment_method' => Session::get('chk_payment_method'),
                'quantity' => Session::get('chk_quantity'),
                'subtotal' => Session::get('chk_subtotal'),
                'discount' => Session::get('chk_discount'),
                'shipping_cost' => Session::get('chk_shipping_cost'),
                'reserved_until' => $reserved_until,
                'shipping_date' => $shipping_date,
                'total' => Session::get('chk_total'),
                'status' => $status,
                'payment_status' => $payment_status,
                'date_paid' => $date_now,
                'paypal_redirect_url' => Session::get('paypal_redirect_url'),
                'cart_products' => Session::get('chk_cart_products')
            );

            $order_number = $this->saveOrder($order_params);

            $customer = Customer::where('id', $customer_id)->first();

            if (Session::get('chk_shipping_method') == 'Shipping')
            {
                $date = strftime("%A, %B %d, %Y", strtotime("+$d_delivery weekday"));
                $days = $d_delivery;
            }
            else
            {
                $date = strftime("%A, %B %d, %Y", strtotime("+$d_reserved weekday"));
                $days = $d_reserved;
            }

            $date_data = ['date'=>$date, 'days'=> $days];

            //Save PayPal Payment Information
            $paypal = new PaypalPayment();
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
            
            $array_params = ['order'=> $orderData];

            $this->createInvoice($array_params);

            try
            {
                // Send Order Confirmation
                $customer->notify(new OrderConfirmation($orderData, $date_data));

                // 
            }
            catch(Exception $ex)
            {
                //dd('paypal 2nd:'.$ex->getMessage());
                //return $e->getMessage();
                return redirect()->route('payment.failed')->with('notify_error', 'There was an error encountered.');
            }

            // Delete cart
            Cart::where('customer_id', $customer_id)->delete();

        }
        else
        {
           
            $orderData->status = 'Failed';
            $orderData->payment_status = 'Pending';
            $orderData->update();

            Session::put('state_error', 'Payment failed');
        
            return Redirect::to('/payment/failed');
        }

            Session::forget('chk_cust_id');
            Session::forget('chk_first_name');
            Session::forget('chk_last_name');
            Session::forget('chk_street');
            Session::forget('chk_province');
            Session::forget('chk_municipal');
            Session::forget('chk_barangay');
            Session::forget('chk_zip_code');
            Session::forget('chk_company');
            Session::forget('chk_phone_number');
            Session::forget('chk_shipping_method');
            Session::forget('chk_payment_method');
            Session::forget('chk_quantity');
            Session::forget('chk_subtotal');
            Session::forget('chk_discount');
            Session::forget('chk_shipping_cost');
            Session::forget('chk_total');
            Session::forget('chk_cart_products');
            Session::forget('paypal_payment_id');
            Session::forget('paypal_redirect_url');
            Session::forget('checkout_details');
            Session::forget('voucher_discount');
            Session::forget('voucher_shipping');
            Session::forget('subtotal');
            Session::forget('shipping_fee');
            Session::forget('total');

            //dd('SUCCESS');
            //return $orderNumber;
            //$orderDetail = Order::where('number', $orderNo)->first();
    
            return redirect()->route('order.received')->with([
                'order'=> $orderData,
                'received_order'=>true
            ]);

            // return redirect()->route('payment.success')->with(['order' => $orderData, 'payment_success'=>true]);
    }

    public function paymentStatus()
    {
        return view('frontend.customer.payment_status');
    }

    public function paymentSuccess()
    {
        if (Session::has('payment_success'))
        {
            $redirect = view('frontend.customer.payment_successful');
        }
        else
        {
            $redirect = redirect()->route('frontend_homepage');
        }
        return $redirect;
    }

    public function paymentFailed()
    {
        return view('frontend.customer.payment_failed');
    }

    public function createInvoice($array_params)
    {
        // set timezone
        date_default_timezone_set("Asia/Manila");

        // create invoice
        $invoice = new Invoice;
        $invoice->number = str_random(4);
        $invoice->order_number = $array_params['order']['number'];
        $invoice->first_name = $array_params['order']['customer']['first_name'];
        $invoice->last_name = $array_params['order']['customer']['last_name'];
        $invoice->subtotal = str_replace(',', '', $array_params['order']['subtotal']);
        $invoice->discount = str_replace(',', '', $array_params['order']['discount']);
        $invoice->shipping_fee = str_replace(',', '', $array_params['order']['shipping_cost']);
        $invoice->total = str_replace(',', '', $array_params['order']['total']);
        $invoice->payment_date = $array_params['order']['date_paid'];
        $invoice->created = date("Y-m-d H:i:s");
        $invoice->status = 'Paid';
        $invoice->save();

        $updateInvoice = Invoice::where('number', $invoice->number)->first();
        $updateInvoice->number = str_pad($updateInvoice->id, 6, '0', STR_PAD_LEFT);
        $updateInvoice->update();

        // save invoice products
        foreach ($array_params['order']['orderProducts'] as $item)
        {
            $invoiceProd = new InvoiceProduct;
            $invoiceProd->invoice_number = $updateInvoice->number;
            $invoiceProd->inventory_sku = $item['inventory_sku'];
            $invoiceProd->product_name = $item['product_name'];
            $invoiceProd->price = str_replace(',', '', $item['price']);
            $invoiceProd->quantity = $item['quantity'];
            $invoiceProd->total = str_replace(',', '', $item['total']);
            $invoiceProd->invoicing_id = $item['ordering_id'];
            $invoiceProd->invoicing_type = $item['ordering_type'];
            $invoiceProd->date_invoice = date('Y-m-d');
            $invoiceProd->save();
        }
    }

    private function saveOrder($array_params)
    {
         // set timezone
        date_default_timezone_set("Asia/Manila");

        try
        {
            // save order
            $order = new Order;
            $order->number = str_random(5);
            $order->customer_id = $array_params['customer_id'];
            $order->first_name = ucfirst($array_params['first_name']);
            $order->last_name = ucfirst($array_params['last_name']);
            $order->street = $array_params['street'];
            $order->province = $array_params['province'];
            $order->municipal = $array_params['municipal'];
            $order->barangay = $array_params['barangay'];
            $order->zip_code = $array_params['zip_code'];
            $order->company = ucfirst($array_params['company']);
            $order->phone_no = $array_params['phone_number'];
            $order->shipping_method = $array_params['shipping_method'];
            $order->payment_method = $array_params['payment_method'];
            $order->quantity = $array_params['quantity'];
            $order->subtotal = $array_params['subtotal'];
            $order->discount = $array_params['discount'];
            $order->shipping_cost = $array_params['shipping_cost'];
            $order->reserved_until = $array_params['reserved_until'];
            $order->shipping_date = $array_params['shipping_date'];
            $order->total = $array_params['total'];
            $order->status = $array_params['status'];
            $order->payment_status = $array_params['payment_status'];
            $order->date_paid = $array_params['date_paid'];
            $order->paypal_redirect_url = $array_params['paypal_redirect_url'];
            $order->date_created = date("Y-m-d H:i:s");
            $order->save();

            $updateOrder = Order::where('number', $order->number)->first();
            $updateOrder->number = str_pad($updateOrder->id, 6, '0', STR_PAD_LEFT);
            $updateOrder->update();

            //Save ordered products
            foreach ($array_params['cart_products'] as $cart) {

                $invent = Inventory::where(['product_sku'=>$cart->product_sku, 'inventorying_id'=>$cart->carting_id])->first();

                $product = Product::where('sku', $cart->product_sku)->first();

                $orderProduct = new OrderProduct;
                $orderProduct->order_number = $updateOrder->number;
                $orderProduct->inventory_sku = $invent->sku;
                $orderProduct->product_name = $invent->name;
                $orderProduct->price = str_replace(',', '', $cart->price);
                $orderProduct->quantity = $cart->quantity;
                $orderProduct->total = str_replace(',', '', $cart->total);
                $orderProduct->ordering_id = $cart->carting_id;
                $orderProduct->ordering_type = $cart->carting_type;
                $orderProduct->status = 'Reserved';
                $orderProduct->save();

            }
            // update inventory
            $this->updateInventory($updateOrder->number);

            return $updateOrder->number;
        }
        catch(Exception $ex)
        {
            dd($ex->getMessage());
        }
    }
}
