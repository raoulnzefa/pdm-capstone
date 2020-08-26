@component('mail::message')
# Hello {{$order->customer->first_name}},

A new order has been generate for your product replacement.

Order Number: {{$order->number}}<br>
Status: {{$order->status}}<br>
Remarks: {{$order->remarks}}

# Order Details

@component('mail::table')
| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($order->orderProducts as $product)
|{{ $product->product_name }}|&#8369;{{ $product->price }}|{{ $product->quantity }}|&#8369;{{ $product->total }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->subtotal }}<br>
@if ($order->order_shipping_method == 'Shipping')
Shipping Fee:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_shipping_fee }}<br>
@endif
Total:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->total }}<br>


@component('mail::button', ['url' => $url])
Order Details
@endcomponent

@if ($order->order_shipping_method == 'Shipping')
Shipping information:

{{ $order->shipping->shipping_firstname.' '.$order->shipping->shipping_lastname }}<br>
{{ $order->shipping->shipping_street.', '.$order->shipping->shipping_barangay }}<br>
{{ $order->shipping->shipping_municipality.', '.$order->shipping->shipping_province.', '.$order->shipping->shipping_zip_code }}<br>
{{ $order->shipping->shipping_mobile_no }}
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
