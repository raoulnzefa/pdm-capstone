<?php

namespace App\Http\Controllers\Api;

use App\ReturnedOrder;
use App\ReturnedProduct;
use App\SalesReturn;
use App\SalesReturnProduct;
use App\ReturnRequest;
use App\ReturnProductRequest;
use App\Order;
use App\OrderProduct;
use App\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReplacementRequestController extends Controller
{
	public function saveReplacementRequest(Request $request, Order $order)
	{
		 //return response()->json($request->replacement_products);
        $insufficient_stock = false;
        // check inventory
        foreach ($request->replacement_products as $item)
        {
        	$orderProd = OrderProduct::where('id', $item['order_product_id'])->first();

            if ($item['return_qty'] > 0)
            {
                $inventory = Inventory::where('sku', $orderProd->inventory_sku)->first();

                if ($inventory->quantity < $item['return_qty'])
                {
                    $insufficient_stock = true;
                }
                else
                {
                    $insufficient_stock = false;
                }
            }
        }
        
        if ($insufficient_stock)
        {
            $response = ['success'=>false];
        }
        else
        {
            $order->status = 'Replaced';
            $order->update();

            // if ($request->has('return_request') && $request->return_request == 'yes')
            // {
            //     
            // }

            $returnRequest = ReturnRequest::where('order_number', $order->number)->first();
            $returnRequest->status = 'Replaced';
            $returnRequest->update();

            $array_params = array(
                'order' => $order,
                'type' => 'Replacement',
                'subtotal'=> $request->subtotal,
                'discount'=> $request->discount,
                'shipping_fee'=>$request->shipping_fee,
                'total'=>$request->total,
                'returned_products' => $request->replacement_products
            );

            $this->saveReturnedOrder($array_params);

            $this->saveSalesReturn($array_params);

            $this->deductInventory($request->replacement_products);

            $response = ['success'=>true];
        }

        return response()->json($response);
	}

    private function saveReturnedOrder($array_params)
    {
        // set time zone
        date_default_timezone_set("Asia/Manila");
        // save retured order
        $returned_order = new ReturnedOrder;
        $returned_order->invoice_number = $array_params['order']['invoice']['number'];
        $returned_order->order_number = $array_params['order']['number'];
        $returned_order->type = $array_params['type'];
        $returned_order->subtotal = $array_params['subtotal'];
        $returned_order->discount = $array_params['discount'];
        $returned_order->shipping_fee = $array_params['shipping_fee'];
        $returned_order->total = $array_params['total'];
        $returned_order->return_created = date('Y-m-d H:i:s');
        $returned_order->save();

        // save returned products
        foreach ($array_params['returned_products'] as $item)
        {
        	$orderProd = OrderProduct::where('id', $item['order_product_id'])->first();

            if ($item['return_qty'] > 0)
            {
                $returned_product = new ReturnedProduct;
                $returned_product->returned_order_id = $returned_order->id;
                $returned_product->inventory_sku = $orderProd->inventory_sku;
                $returned_product->price = str_replace(',', '', $orderProd->price);
                $returned_product->quantity = $item['return_qty'];
                $returned_product->amount = $item['return_qty'] * str_replace(',', '', $orderProd->price);
                $returned_product->save();
            }
        }
    }

    private function saveSalesReturn($array_params)
    {
        // set time zone
        date_default_timezone_set("Asia/Manila");

        $salesReturn = new SalesReturn;
        $salesReturn->number = str_random(5);
        $salesReturn->invoice_number = $array_params['order']['invoice']['number'];
        $salesReturn->customer_id = $array_params['order']['customer_id'];
        $salesReturn->order_number = $array_params['order']['number'];
        $salesReturn->type = $array_params['type'];
        $salesReturn->return_created = date('Y-m-d H:i:s');
        $salesReturn->subtotal = $array_params['subtotal'];
        $salesReturn->discount = $array_params['discount'];
        $salesReturn->shipping_fee = $array_params['shipping_fee'];
        $salesReturn->total = $array_params['total'];
        $salesReturn->save();

        // update sales return number
        $updateSalesReturnNum = SalesReturn::where('number', $salesReturn->number)->first();
        $updateSalesReturnNum->number = str_pad($updateSalesReturnNum->id, 6, '0', STR_PAD_LEFT);
        $updateSalesReturnNum->update();

        // save return product
        foreach ($array_params['returned_products'] as $item)
        {
        	$orderProd = OrderProduct::where('id', $item['order_product_id'])->first();

            if ($item['return_qty'] > 0)
            {
                $salesReturnProd = new SalesReturnProduct;
                $salesReturnProd->sales_return_number = $updateSalesReturnNum->number;
                $salesReturnProd->inventory_sku = $orderProd->inventory_sku; 
                $salesReturnProd->quantity = $item['return_qty'];
                $salesReturnProd->amount = $item['return_qty'] * str_replace(',', '', $orderProd->price);
                $salesReturnProd->save();
            }
        }
    }

    private function deductInventory($returned_prod)
    {
        // deduct inventory
        foreach ($returned_prod as $item)
        {
        	$orderProd = OrderProduct::where('id', $item['order_product_id'])->first();

            $inventory = Inventory::where('sku', $orderProd->inventory_sku)->first();
            $inventory->quantity -= $item['return_qty'];
            
            if ($inventory->quantity > 0)
            {
                $availability = 'In stock';
            }
            else
            {
                $availability = 'Out of stock';
            }
            $inventory->availability = $availability;
            $inventory->update();
        }   
    }
}
