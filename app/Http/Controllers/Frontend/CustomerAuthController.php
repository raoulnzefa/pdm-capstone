<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CustomerAddress;
use App\Notifications\ActivationLink;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;
use Validator;
use Session;
use Carbon\Carbon;

class CustomerAuthController extends Controller
{
	public function __construct()
	{
		$this->middleware('guest:customer', ['except' => ['logout']]);
	}

    public function showLoginForm()
    {
        $redirect_back = url()->previous();

    	return view('frontend.login')->with([
                    'data' => 'Login',
                    'prev_url' => $redirect_back
                ]);
    }

    public function login(Request $request)
    {

    	$request->validate([
    		'email'	=>	'required|email',
    		'password'	=>	'required|min:6'
    	]);

    	if (Auth::guard('customer')->attempt(['email'=>$request->email,'password'=>$request->password, 'status'=>'Active']))
    	{
            // $cust_email = Customer::where('email',Auth::guard('customer')->user()->email)->first()->email;
            
            //return redirect()->intended(route('customer.my-account'));    
            return redirect()->route('customer.orders');

    	}

        // If unsuccessfull
        return redirect()->back()->withInput(['email'=> $request->email])->with(['cust_login_failed'=>"Your email or password is incorrect. Please try again. If you've forgotten your login details, just click the 'Forgot your password?' link below."]);
    }

    public function showRegisterForm()
    {
        $redirect_back = url()->previous();

        return view('frontend.registration')->with([
            'data'=> 'Create an Account',
            'prev_url' => $redirect_back
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'email' => 'required|string|email|unique:customers',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
            'street' => 'required',
            'province' => 'required',
            'municipality' => 'required',
            'barangay' => 'required',
            'zip_code' => 'required',
            'mobile_no' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/create-account')
                        ->withErrors($validator)
                        ->withInput($request->except('password'));
        }

        // set Manila timezone
        date_default_timezone_set("Asia/Manila");

        $cust = new Customer();
        $cust->email = $request->email;
        $cust->first_name = ucwords($request->first_name);
        $cust->last_name = ucwords($request->last_name);
        $cust->password = Hash::make($request->password);
        $cust->status = 'Inactive';
        $cust->token = Str::random(40);
        $cust->registered = date('Y-m-d H:i:s');
        $cust->save();

        $addrCount = CustomerAddress::where('customer_id', $cust->id)->count();

        $custAddress = new CustomerAddress();
        $custAddress->customer_id = (int)$cust->id;
        $custAddress->address_name = ($addrCount < 1) ? 'Address 1'  : 'Address '.$addrCount;
        $custAddress->firstname = ucwords($request->first_name);
        $custAddress->lastname = ucwords($request->last_name);
        $custAddress->street = ucfirst($request->street);
        $custAddress->barangay = $request->barangay_name;
        $custAddress->municipality = $request->municipality_name;
        $custAddress->province = $request->province_name;
        $custAddress->barangay_id = (int)$request->barangay;
        $custAddress->municipality_id = (int)$request->municipality;
        $custAddress->province_id = (int)$request->province;
        $custAddress->zip_code = $request->zip_code;
        $custAddress->mobile_no = $request->mobile_no;
        $custAddress->save();

        $cust_data = Customer::where('id', $cust->id)->first();
        // $cust_data->number = 'CUS-'.date('Y').'-'.str_pad($cust_data->id, 4, '0', STR_PAD_LEFT);

        try {
            $cust_data->notify(new ActivationLink($cust_data)); 
        } catch(Exception $e) {
            Customer::where('email', $request->email)->delete();
            //return $e->getMessage();
            return redirect()->back()->with('registration_error', 'Registration failed: '.$e->getMessage());
        }
        
        
        //return redirect()->route('customer_login')->with('status', 'Thank you for registration. A verification link has been emailed to '.$request->email.'.');

        return redirect()->route('account.created')->with('email_created', $request->email);
    }

    public function emailVerified($email, $token)
    {
        $exists = Customer::where(['email'=>$email, 'token'=>$token])->exists();

        if ($exists)
        {
            $customer = Customer::where(['email'=>$email, 'token'=>$token])->first();
            $customer->status = "Active";
            $customer->token = NULL;
            $customer->update();
                       
            return redirect()->route('customer_verified')->with('email_verified', $email);   
        }
        else
        {
            return view('frontend.customer.email_not_found');
        }
        
    }

    public function logout(Request $request)
    {
        
        Auth::guard('customer')->logout();

        $cust = Customer::where('email',$request->email)->first();

        $action = $request->action;
        
        if ($action === 'new_email')
        {
            $cust->notify(new ActivationLink($cust));

            return redirect()->route('customer_login')->with('status', 'Your activation link was mail to '.$request->email);;
        }
        else
        {
            return redirect()->route('frontend_homepage');
        }
    }

    public function accountCreated()
    {
        if (Session::has('email_created'))
        {
            $data = 'Account Created';
            $redirect = view('frontend.customer.account_created', compact('data'));
        }
        else
        {
            $redirect = redirect()->route('frontend_homepage');
        }
        return $redirect;
    }

    public function customerVerified()
    {
        if (Session::has('email_verified'))
        {
            $data = 'Email Verified';
            $redirect = view('frontend.customer.email_verified', compact('data'));
        }
        else
        {
            $redirect = redirect()->route('frontend_homepage');
        }

        return $redirect;
    }

   
}
