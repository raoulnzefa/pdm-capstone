<?php

namespace App\Http\Controllers\Api;

use App\Notifications\DeclinedReplacementNotification;
use App\Notifications\AcceptedReplacementNotification;
use App\Models\ReplacementRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReplacementRequestController extends Controller
{
	public function getCustomerRequests($customerId)
    {
        $replacements = ReplacementRequest::where('customer_id',$customerId)
            ->with('inventory.product')->get();

        return response()->json($replacements);
    }

    public function getReplacements()
    {
        $replacements = ReplacementRequest::with('inventory.product')->get();

        return response()->json($replacements);
    }

    public function details($requestId)
    {
        $details = ReplacementRequest::where('id',$requestId)->with('inventory.product')->first();

        return response()->json($details);
    }

    public function acceptRequest()
    {

    }

    public function declineRequest()
    {
        
    }
}
