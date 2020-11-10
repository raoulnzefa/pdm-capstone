<?php

namespace App\Http\Controllers\Api;

use App\Notifications\DeclinedReplacementNotification;
use App\Notifications\ApprovedReplacementNotification;
use App\Notifications\ReplacedProductNotification;
use App\Models\ReplacementRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\ReplacementProductPhoto;
use App\Models\DefectiveProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\UserLogs;
use App\Http\Controllers\Traits\InventoryManager;
use Exception;
use Illuminate\Support\Facades\Storage;

class ReplacementRequestController extends Controller
{

    use UserLogs, InventoryManager;

    public function submitRequest(Request $request)
    {
        $request->validate([
            'reason' => 'required',
            'quantity' => 'required',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        date_default_timezone_set("Asia/Manila");

        //dd($request->all());
        $replacement = new ReplacementRequest();
        $replacement->customer_id = (int)$request->customer;
        $replacement->order_number = $request->order_number;
        $replacement->inventory_number = $request->inventory_number;
        $replacement->product_name = $request->product_name;
        $replacement->quantity = (int)$request->quantity;
        $replacement->reason = ucfirst($request->reason);
        $replacement->status = 'Pending';
        $replacement->request_created = date('Y-m-d H:i:s');
        $replacement->status_update = 1;
        $replacement->save();

        $response = [];

        if ($request->hasFile('photos'))
        {
            foreach ($request->file('photos') as $photo ) {
                try
                {
                    // set image name
                    $imageName = time().'.'.str_replace(' ', '-', $photo->getClientOriginalName());

                    // save image into storage
                    $path = $photo->storeAs(
                        'replacement_photos',
                        $imageName,
                        's3'
                    );

                }
                catch (Exception $e)
                {
                    $response = ['error' => $e->getMessage()];
                }
               
                $photos = new ReplacementProductPhoto();
                $photos->replacement_request_id = (int)$replacement->id;
                $photos->product_photo_url = Storage::disk('s3')->url($path);
                $photos->product_photo_path = $path;
                $photos->save();

            }
        }
        
        return response()->json(['success' => true]);


    }

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
        $details = ReplacementRequest::where('id',$requestId)->with('inventory.product','replacementProductPhotos')->first();

        return response()->json($details);
    }

    public function approveRequest(Request $request)
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

            $replacement->status = 'Approved';
            $replacement->status_update = 1;
            $replacement->request_approved = date('Y-m-d H:i:s');
            $replacement->update();

            $customer = Customer::where('id',$replacement->customer_id)->first();
            // send email
            $customer->notify(new ApprovedReplacementNotification($replacement));

            $array_params = [
                'id' => $request->admin_id,
                'action' => 'Approved replacement request of ID: '.$replacement->id
            ];

            $this->createUserLog($array_params);

            $response = ['success'=>true];
        }
        catch(Exception $ex)
        {
            $response = ['success' => false, 'msg' => $ex->getMessage()];
        }

        return response()->json($response);

    }

    public function declineRequest(Request $request)
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
            $replacement->status_update = 1;
            $replacement->request_declined = date('Y-m-d H:i:s');
            $replacement->update();

            $customer = Customer::where('id',$replacement->customer_id)->first();
            // send email
            $customer->notify(new DeclinedReplacementNotification($replacement));

            $array_params = [
                'id' => $request->admin_id,
                'action' => 'Declined replacement request of ID: '.$replacement->id
            ];

            $this->createUserLog($array_params);

            $response = ['success'=>true];
        }
        catch(Exception $ex)
        {
            $response = ['success' => false, 'msg' => $ex->getMessage()];
        }

        return response()->json($response);
    }

    public function replaceProduct(Request $request)
    {
        $request->validate([
            'replacement_id'=> 'required'
        ]);

        date_default_timezone_set("Asia/Manila");

        $replacement = ReplacementRequest::where('id', (int)$request->replacement_id)->first();
        $inventory = Inventory::where('number', $replacement->inventory_number)->first();

        if ($inventory->inventory_stock >= $replacement->quantity)
        {
           

            if ($request->additional_action == 'restock')
            {
                $this->restockItem($replacement->inventory_number, (int)$replacement->quantity);

                $inventory->inventory_stock -= $replacement->quantity;
                $inventory->update();
            }
            elseif ($request->additional_action == 'record_as_defective')
            {
                $defective = new DefectiveProduct();
                $defective->inventory_number = $replacement->inventory_number;
                $defective->replacement_request_id = (int)$replacement->id;
                $defective->save();

                $inventory->inventory_stock -= $replacement->quantity;
                $inventory->update();
            }
            else
            {
                $inventory->inventory_stock -= $replacement->quantity;
                $inventory->update();
            }

            $replacement->status = 'Replaced';
            $replacement->status_update = 1;
            $replacement->request_replaced = date('Y-m-d H:i:s');
            $replacement->update();

            $customer = Customer::where('id',$replacement->customer_id)->first();
            // send email
            $customer->notify(new ReplacedProductNotification($replacement));


            $array_params = [
                'id' => $request->admin_id,
                'action' => 'Replaced product. Inventory number: '.$replacement->inventory_number.', Order number: '.$replacement->order_number.', Quntity: '.$replacement->quantity
            ];

            $this->createUserLog($array_params);

            if (!is_null($request->additional_action))
            {

                $restock_msg = 'Restock product. Inventory number: '.$replacement->inventory_number.', Quntity: '.$replacement->quantity;

                $defective_msg = 'Recorded as defective product. Inventory number: '.$replacement->inventory_number.', Quntity: '.$replacement->quantity;

                $array_params = [
                    'id' => $request->admin_id,
                    'action' => ($request->additional_action == 'restock') ? $restock_msg : $defective_msg
                ];

                $this->createUserLog($array_params);
            }

            return response()->json(['success'=>true]);
        }

    }

    public function replacementStatusUpdate($customer)
    {
      try
      {
        $has_order = Order::where('customer_id', $customer)->count();

        if ($has_order > 0)
        {
            $status_update = ReplacementRequest::where('status_update','=',1)->count();    
        } 
        else
        {
            $status_update = null;
        }

        $response = ['count'=> $status_update];
      }
      catch(Exception $e)
      {
         $response = ['err' =>$e->getMessage()];
      }

      return response()->json($response);
        
   }

    public function updateStatus(ReplacementRequest $replacement)
    {
        $replacement->status_update = 0;
        $replacement->update();

        return response()->json(['success' => true]);
    }

    public function getViewed()
    {
        try
      {
         $status_update = ReplacementRequest::where('viewed','=',0)->count();

         $response = ['count'=> $status_update];
      }
      catch(Exception $e)
      {
         $response = ['err' =>$e->getMessage()];
      }

      return response()->json($response);
    }
}
