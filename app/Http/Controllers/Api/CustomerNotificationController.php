<?php

namespace App\Http\Controllers\Api;

use App\Order;
use App\CancelOrderRequest;
use App\ReturnRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerNotificationController extends Controller
{
    public function orderNotif()
    {

    }

    public function cancellationNotif()
    {
    	$updated_status = CancelOrderRequest::where('status_update','=',1)->count();

    	if ($updated_status > 0)
    	{
    		$response = true;
    	}
    	else
    	{
    		$response = false;
    	}

    	return response()->json($response);
    }

    public function returnNotif()
    {
    	
    }
}
