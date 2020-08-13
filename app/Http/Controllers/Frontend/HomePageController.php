<?php

namespace App\Http\Controllers\Frontend;

use App\Notifications\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Invoice;
use App\Models\Product;
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
