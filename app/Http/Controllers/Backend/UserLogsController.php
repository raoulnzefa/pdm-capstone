<?php

namespace App\Http\Controllers\Backend;

use App\UserLog; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserLogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
    	return view('backend.user_logs.index');
    }
}
