<?php

namespace App\Http\Controllers\Backend;

use App\Models\Customer;
use App\Models\Order;
use App\Models\BankAccount;
use App\Models\CompanyDetails;
use App\Models\Admin;
use App\Models\UserLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\UserLogs;
use App\Notifications\BankDepositEmail; 
use Exception;

class AdminAuthController extends Controller
{
    use UserLogs;

	public function __construct()
	{
		$this->middleware('guest:admin', ['except' => ['logout']]);
	}

    public function registrationForm()
    {
        $data = 'Register Account';
        $company = CompanyDetails::first();
        return view('backend.registration',compact('data','company'));
    }

    public function registrationSuccess()
    {
        // $adminAccount = Admin::where('status', 'Active')->count();

        // if ($adminAccount <= 0)
        // {
        //     return redirect()->route('admin.registration');
        // }
        // elseif ($adminAccount > 0)
        // {
        //     $data = 'Registration Success';

        //     return view('backend.success_registration',compact('data'));
        // }

        $data = 'Registration Success';
        $company = CompanyDetails::first();
        return view('backend.success_registration',compact('data', 'company'));


    }

    public function showLoginForm()
    {
        $adminAccount = Admin::where('status', 'Active')->count();

        if ($adminAccount <= 0)
        {
            return redirect()->route('admin.registration');
        }

        $data = 'Login';
        $company = CompanyDetails::first();
    	return view('backend.login_form', compact('data','company'));
    }

    public function login(Request $request)
    {
    	// Validate the form data
    	$request->validate([
    		'username' => 'required',
    		'password' => 'required|min:6'
    	]);

        date_default_timezone_set("Asia/Manila");
    	// Attempt to log the use in
    	if (Auth::guard('admin')->attempt(
                    [
                        'username' => $request->username,
                        'password'=>$request->password,
                        'status'=>'Active'
                    ]))
    	{
            $admin = Auth::guard('admin')->user();
            //send email
            try
            {
                $orders = Order::where('order_payment_method','Bank Deposit')
                    ->where('order_payment_status', 'Pending')
                    ->where('order_sent_follow_up', 0)
                    ->where(function($query) {
                            $query->whereRaw('order_follow_up_email = CURRENT_DATE');
                });

                if (count($orders) > 0)
                {

                    // get bank account
                    $bank_account = BankAccount::where('active', 1)->first();


                    foreach ($orders as $item) {

                        if ($item->order_sent_follow_up > 0)
                        {    
                            $days = $item->order_due_payment_days;
                            $due_date_email = strftime("%A, %B %d, %Y", strtotime("+days weekday"));

                            $dateData = ['date'=>$due_date_email,'days'=> $days];
                            
                            $customer = Customer::where('id',$item->customer_id)->first();

                            $customer->notify(new BankDepositEmail($item, $dateData, $bank_account));
                        }
                    }
                }

            }
            catch(Exception $ex)
            {

            }

            $array_params = [
                'id' => $admin->id,
                'action' => 'Logged In. User ID: '.$admin->id.', Admin Name: '.$admin->first_name.' '.$admin->last_name.'.'
            ];

            $this->createUserLog($array_params);
           
    		return redirect()->route('admin_dashboard');
    	}

    	// If unsuccessfull
    	return redirect()->back()->withInput(['username'=> $request->username])->with(['login_failed'=>"Your have entered an invalid username or password."]);
    }

    public function logout()
    {   
        $admin = Auth::guard('admin')->user();

        $array_params = [
            'id' => $admin->id,
            'action' => 'Logged Out. User ID: '.$admin->id.', Admin Name: '.$admin->first_name.' '.$admin->last_name.'.'
        ];

        $this->createUserLog($array_params); 

        // logout admin session
        Auth::guard('admin')->logout();
        
        return redirect()->route('admin_login');
    }

    public function showLinkRequestForm()
    {
        return view('backend.request_link_form');
    }

   
}
