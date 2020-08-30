<?php

namespace App\Http\Controllers\Api;

use App\Notifications\DeclinedReplacementNotification;
use App\Notifications\AcceptedReplacementNotification;
use App\Models\ReplacementRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Customer;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

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
        $request->validate([
            'replacement_id' => 'required'
        ]);

        date_default_timezone_set("Asia/Manila");

        try
        {
            $replacement = ReplacementRequest::where([
                'id' => $request->replacement_id,
                'status' => 'Pending'
            ])->first();

            $replacement->status = 'Accepted';
            $replacement->request_accepted = date('Y-m-d H:i:s');
            $replacement->update();

            $customer = Customer::where('id',$replacement->customer_id)->first();
            // send email
            $customer->notify(new AcceptedReplacementNotification($replacement));

            $response = ['success'=>true];
        }
        catch(Exception $ex)
        {
            $response = ['success' => false, 'msg' => $ex->getMessage()];
        }

        return response()->json($response);

    }

    public function declineRequest()
    {
        $request->validate([
            'replacement_id' => 'required'
        ]);

        date_default_timezone_set("Asia/Manila");

        try
        {
            $replacement = ReplacementRequest::where([
                'id' => $request->replacement_id,
                'status' => 'Pending'
            ])->first();

            $replacement->status = 'Declined';
            $replacement->request_declined = date('Y-m-d H:i:s');
            $replacement->update();

            $customer = Customer::where('id',$replacement->customer_id)->first();
            // send email
            $customer->notify(new DeclinedReplacementNotification($replacement));

            $response = ['success'=>true];
        }
        catch(Exception $ex)
        {
            $response = ['success' => false, 'msg' => $ex->getMessage()];
        }

        return response()->json($response);
    }
}
