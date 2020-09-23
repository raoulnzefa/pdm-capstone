<?php

namespace App\Http\Controllers\Traits;

use App\Models\Invoice;
use App\Models\InvoiceProduct;


trait InvoiceTraits
{
        public function createInvoice($array_params)
        {
                // set timezone
                date_default_timezone_set("Asia/Manila");

                // create invoice
                $invoice = new Invoice;
                $invoice->number = str_random(4);
                $invoice->order_number = $array_params['order']['number'];
                $invoice->first_name = $array_params['order']['customer']['first_name'];
                $invoice->last_name = $array_params['order']['customer']['last_name'];
                $invoice->subtotal = str_replace(',', '', $array_params['order']['order_subtotal']);
                $invoice->discount = str_replace(',', '', $array_params['order']['order_discount']);
                $invoice->shipping_fee = str_replace(',', '', $array_params['order']['order_shipping_fee']);
                $invoice->total = str_replace(',', '', $array_params['order']['order_total']);
                $invoice->payment_date = $array_params['order']['order_payment_date'];
                $invoice->created = date("Y-m-d H:i:s");
                $invoice->status = 'Paid';
                $invoice->save();

                $updateInvoice = Invoice::where('number', $invoice->number)->first();
                $updateInvoice->number = str_pad($updateInvoice->id, 6, '0', STR_PAD_LEFT);
                $updateInvoice->update();

                // save invoice products
                foreach ($array_params['order']['orderProducts'] as $item)
                {
                    $invoiceProd = new InvoiceProduct;
                    $invoiceProd->invoice_number = $updateInvoice->number;
                    $invoiceProd->inventory_number = $item['inventory_number'];
                    $invoiceProd->product_name = $item['product_name'];
                    $invoiceProd->price = str_replace(',', '', $item['price']);
                    $invoiceProd->quantity = $item['quantity'];
                    $invoiceProd->total = str_replace(',', '', $item['total']);
                    $invoiceProd->date_invoice = date('Y-m-d');
                    $invoiceProd->save();
                }
        }

        
}
