<?php

namespace App\Http\Controllers\Api;


use App\Notifications\OrderPickedUpNotification;
use App\Notifications\PaymentReceived;
use App\Notifications\DeliveryConfirmation;
use App\Http\Controllers\Traits\UserLogs;
use App\Http\Controllers\Traits\InvoiceTraits;
use App\Http\Controllers\Traits\InventoryManager;
use App\Models\Order;
use App\Models\OrdeProduct;
use App\Models\Customer;
use App\Models\StorePickup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Carbon\Carbon;

class OrderController extends Controller
{
   use UserLogs, InvoiceTraits, InventoryManager;

   public function viewOrders(Request $request)
    {  
        $today = Carbon::today();
        // chechk query parameter
        if ($request->query('search'))
        {
            // search value
            $search_val = $request->query('search');
            // get orders base on order number
            $orders = Order::where('number',$search_val)
                            ->with('customer')->paginate(10);

            // appends to pagination
            $orders->appends($request->only('search'));
        }
        elseif ($request->query('from') && $request->query('to'))
        {
            // set timezone
            date_default_timezone_set("Asia/Manila");
            // set variables
            $from_date = $request->query('from');
            $to_date = $request->query('to');

            $orders = Order::whereBetween('date_order', [$from_date, $to_date])
                            ->with('customer')
                            ->paginate(10);

            //$orders->appends($request->only(['from', 'to']));
        }
        elseif ($request->query('status'))
        {
            // order status
            $order_status = $request->query('status');
            // get orders
            $orders = Order::where('order_status', $order_status)
                            ->with('customer')->paginate(10);

            //$orders->appends($request->only('status'));
        }
        else
        {

             $orders = Order::orderBy('viewed','asc')
                        ->with('customer')->paginate(10);

        }
    

      return response()->json($orders);
    }

   public function cancelOrderByCustomer(Request $request, Order $order)
   {
      date_default_timezone_set("Asia/Manila");

      if ($order->order_payment_status == 'Pending')
      {
        $order->order_status = 'Cancelled';
        $order->order_cancelled = date('Y-m-d H:i:s');
        $order->order_remarks = 'Cancelled by customer';
        $order->update();

        $this->restockOrder($order->number);

        return response()->json(['success'=> true]);
      }
      
   }

   public function pickedUpOrder(Request $request, Order $order)
   {
      date_default_timezone_set("Asia/Manila");

      $days_warranty = 7;
        
      $warranty_date = strftime("%Y-%m-%d", strtotime("+$days_warranty weekday"));

      $order->order_payment_status = 'Paid';
      $order->order_status = 'Completed';
      $order->order_payment_date = date('Y-m-d H:i:s');
      $order->order_completed = date('Y-m-d H:i:s');
      $order->order_warranty = date($warranty_date.' 17:00:00');
      $order->update();

      $store_pickup = StorePickup::where('order_number', $order->number)->first();
      $store_pickup->pickup_in_store = date('Y-m-d H:i:s');
      $store_pickup->update();

      $invoice_params = ['order'=> $order ];

      $this->createInvoice($invoice_params);

      $userlog_params = [
         'id' => $request->admin_id,
         'action' => 'Mark as completed: Order #'.$order->number.'.'
      ];

      $this->createUserLog($userlog_params);

      return response()->json(['success'=>true]);
   }

   public function markAsPaid(Request $request, Order $order)
   {
      date_default_timezone_set("Asia/Manila");

      if ($order->order_payment_method == 'Bank Deposit')
      {
         try
         {
            $order->order_status = 'Processing';
            $order->order_payment_status = $request->payment_status;
            $order->order_payment_date = date('Y-m-d H:i:s');
            $order->update();

            $invoice_params = ['order'=> $order ];

            $this->createInvoice($invoice_params);

            $userlog_params = [
                'id' => $request->admin_id,
                'action' => 'Mark as paid: Order #'.$order->number.'.'
            ];

            $this->createUserLog($userlog_params);

            $customer = Customer::where('id', (int)$order->customer_id)->first();

            $processing_days = 3;

            $date = strftime("%A, %B %d, %Y", strtotime("+$processing_days weekday"));

            $dateData = ['date'=>$date, 'days'=> $processing_days];

            $customer->notify(new PaymentReceived($order, $dateData));

            return response()->json(['success'=>true]);

         // send email
         }
         catch(Exception $ex)
         {
            return response()->json(['success' => false, 'message'=> $ex->getMessage() ]);
         }

      }
      elseif ($order->order_payment_method == 'Cash')
      {

      }
   }

   public function deliverOrder(Request $request, Order $order)
   {
      date_default_timezone_set("Asia/Manila");

      try
      {
         $days_warranty = 7;
        
         $warranty_date = strftime("%Y-%m-%d", strtotime("+$days_warranty weekday"));

         $order->order_status = 'Shipped';
         $order->order_warranty = date($warranty_date.' 17:00:00');
         $order->order_shipped = date('Y-m-d H:i:s');
         $order->update();

         $customer = Customer::where('id',(int)$order->customer_id)->first();

         $customer->notify(new DeliveryConfirmation($order));


         $userlog_params = [
             'id' => $request->admin_id,
             'action' => 'Mark as shipped. Order #: '.$order->number.'.'
         ];

         $this->createUserLog($userlog_params);

         return response()->json(['success'=> true]);

      }
      catch(Exception $ex)
      {
         return response()->json(['success'=> false, 'message'=> $ex->getMessage()]);
      }

   }

   public function markAsCompleted(Request $request, Order $order)
   {
      date_default_timezone_set("Asia/Manila");

    
      $order->order_status = 'Completed';
      $order->order_completed = date('Y-m-d H:i:s');
      $order->order_remarks = 'Mark as completed by owner';
      $order->update();

      $userlog_params = [
          'id' => $request->admin_id,
          'action' => 'Mark as completed. Order #: '.$order->number.'.'
      ];

      $this->createUserLog($userlog_params);

      $response = ['success'=> true];
        

      return response()->json($response);
     
   }

   public function customerReceiveOrder(Request $request, Order $order)
   {
      date_default_timezone_set("Asia/Manila");

      $order->order_status = 'Completed';
      $order->order_remarks = 'Received order by customer';
      $order->order_completed = date('Y-m-d H:i:s');
      $order->update();

      return response()->json(['success'=> true]);
   }

   public function getOrder($orderNum)
   {
      $order = Order::where('number',$orderNum)
            ->with('shipping','orderProducts.inventory.product','bankDepositSlip')
            ->first();

      return response()->json($order);
   }

   public function orderNotview()
   {
      try
      {
         $order_not_viewed = Order::where('viewed','=',0)->count();

         $response = ['count'=> $order_not_viewed];
      }
      catch(Exception $e)
      {
         $response = ['err' =>$e->getMessage()];
      }

      return response()->json($response);
        
   }
    
   public function updateOrderViewed(Order $order)
   {
      $order->viewed = 1;
      $order->update();

      return response()->json(['success'=>true]);
   }

   public function customerOrders(Request $request, $customerId)
   {
      // chechk query parameter
      if ($request->query('search'))
      {
          // search value
          $search_val = $request->query('search');
          // get orders base on order number
          $orders = Order::where(['number'=>$search_val,'customer_id'=>$customerId])->paginate(10);

          // appends to pagination
          $orders->appends($request->only('search'));
      }
      elseif ($request->query('status'))
      {
          // order status
          $order_status = $request->query('status');
          // get orders
          $orders = Order::where(['order_status'=> $order_status, 'customer_id'=>$customerId])->paginate(10);
      }
      else
      {
          $orders = Order::where('customer_id',$customerId)->orderBy('viewed','asc')->paginate(10);

      }

      return response()->json($orders);
   }
}
