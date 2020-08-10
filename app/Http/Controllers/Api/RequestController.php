<?php

namespace App\Http\Controllers\Api;

use App\CancelOrderRequest;
use App\ReturnRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RequestController extends Controller
{
    public function getRequestNotViewed()
    {
    	$cancel_request_count = CancelOrderRequest::where('viewed','=',0)->count();
    	$return_request_count = ReturnRequest::where('viewed','=',0)->count();

    	$all_request_count = $cancel_request_count + $return_request_count;

    	return response()->json(['count' => (int)$all_request_count]);
    }
}
