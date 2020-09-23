<?php

namespace App\Http\Controllers\Backend;

use App\Models\Admin;
use App\Models\UserLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
	public function __construct()
	{
		$this->middleware('guest:admin', ['except' => ['logout']]);
	}

    public function registrationForm()
    {
        $data = 'Register Account';

        return view('backend.registration',compact('data'));
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

        return view('backend.success_registration',compact('data'));


    }

    public function showLoginForm()
    {
        $adminAccount = Admin::where('status', 'Active')->count();

        if ($adminAccount <= 0)
        {
            return redirect()->route('admin.registration');
        }

        $data = 'Login';

    	return view('backend.login_form', compact('data'));
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
           
    		return redirect()->route('admin_dashboard');
    	}

    	// If unsuccessfull
    	return redirect()->back()->withInput(['username'=> $request->username])->with(['login_failed'=>"Your have entered an invalid username or password."]);
    }

    public function logout()
    {   

        // logout admin session
        Auth::guard('admin')->logout();
        
        return redirect()->route('admin_login');
    }

    public function showLinkRequestForm()
    {
        return view('backend.request_link_form');
    }

   
}
