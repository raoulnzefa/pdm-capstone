<?php

namespace App\Http\Controllers\Backend;

use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function editAccount($admin)
    {
        $admin_details = Admin::where('id', $admin)->first();
        
        return view('backend.user.edit-account')->with(['admin'=>$admin_details]);
    }

    public function changePass($admin)
    {
        $admin_details = Admin::where('id', $admin)->first();

        return view('backend.user.change-password')->with(['admin'=>$admin_details]);
    }

    public function create()
    {
        return 'CReate';
    }
}
