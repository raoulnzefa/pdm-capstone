<?php

namespace App\Http\Controllers\Frontend;

use App\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;

class CartController extends Controller
{
    public function __construct()
    {
    	return $this->middleware('auth:customer');
    }

    public function index()
    {
    	$data = 'Cart';	
    	return view('frontend.cart', compact('data'));
    }
}
