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
Shipping Fee:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->shipping_cost }}<br>
Total:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->total }}<br>


@component('mail::button', ['url' => $url])
Order Details
@endcomponent

Shipping information:

{{ $order->first_name.' '.$order->last_name }}<br>
{{ $order->street.', '.$order->barangay }}<br>
{{ $order->municipal.', '.$order->province.', '.$order->zip_code }}<br>
@if (!empty($order->company))
{{ $order->company }}<br>
@endif
{{ $order->phone_no }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
