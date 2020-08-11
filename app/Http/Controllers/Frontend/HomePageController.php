<?php

namespace App\Http\Controllers\Frontend;

use App\CancelProduct;
use App\Notifications\OrderConfirmation;
use App\Order;
use App\OrderProduct;
use App\Invoice;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;

class HomePageController extends Controller
{
    public function index()
    {   

        $data = 'Home';
    	return view('frontend.home', compact('data'));

       
    }
}
