<?php

namespace App\Http\Controllers\Api;

use App\Models\Province;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    
    public function update(Request $request, Customer $customer)
    {
        try
        {

            $request->validate([
                "first_name' => 'required|string|max:20,first_name,$customer->id",
                "last_name' => 'required|string|max:20,last_name,$customer->id",
                'password' => 'sometimes|nullable|min:6|confirmed',
                'password_confirmation' => 'sometimes|nullable|min:6'
            ]);

            $customer->first_name = ucfirst($request->first_name);
            $customer->last_name = ucfirst($request->last_name);
                
            if ($request->filled(['password','password_confirmation']))
            {
                $customer->password = Hash::make($request->input('password'));
            }

            $customer->update();

            $response = array('success'=>true,'message'=>'Your account details has been successfully updated.');
        }
        catch(Exception $ex)
        {
            $response = $ex->getMessage();
        }


        return response()->json($response);
    }

    public function customerName(Customer $customer)
    {
        return response()->json(array('success'=>true, 'cust'=>$customer));
    }

    public function customerPendingOrder($customer)
    {
        $pending_orders = Order::where('customer_email', $customer)->whereRaw('status != "Complete"')->get();

        return response()->json($pending_orders);
    }

    public function completedOrders($customer)
    {
        $completed = Order::where('customer_email', $customer)->where('status', "Complete")->get();

        return response()->json($completed);
    }

    public function list()
    {
        $list = Customer::paginate(10);

        return response()->json($list);
    }

    public function customerReport()
    {
        $list = Customer::get();

        // set timezone
        date_default_timezone_set("Asia/Manila");
        
        $now = date('F d, Y h:i A');


        return response()->json([
            'customers' => $list,
            'date_now'=>$now
        ]);
    }

    public function changePassword(Request $request, Customer $customer)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'
        ]);

        if ($request->has(['password', 'password_confirmation', 'current_password']))
        {

            if (Hash::check($request->current_password, $customer->password))
            {
                $customer->password = Hash::make($request->password);
            }
            else
            {
                $response = array('current_pass'=>'invalid', 'message'=>'Please enter your correct current password.');

                return response()->json($response);
            }
    
        }

        if ($customer->update())
        {
            $response = array('success' => true);
        }

        return response()->json($response);
    }

    public function numberOfCustomers()
    {
        $no_of_customers = Customer::where('status', 'Active')->count();

        return response()->json($no_of_customers);
    }

    public function details($customerId)
    {
        $basic_info = Customer::where('id',$customerId)->first();

        $addresses = Address::where('customer_id', $customerId)->get();

        $orders = Order::where('customer_id', $customerId)->with('orderProducts.inventory.product','shipping')->get();

        return response()->json([
            'basic_info' => $basic_info,
            'addresses' => $addresses,
            'orders' => $orders
        ]);
    }

}
