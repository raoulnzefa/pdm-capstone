@component('mail::message')
# Dear {{ $order->customer->first_name }},

Following order has been cancelled deu to pending payment.

Order Number: {{$order->number}}
Date Order: {{ $order->order_created }}
Status: {{ $order->order_status }}

@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($order->orderProducts as $product)
|{{ $product->product_name }}|&#8369;{{ $product->price }}|{{ $product->quantity }}|&#8369;{{ $product->total }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_subtotal }}<br>
@if ($order->order_shipping_method == 'Shipping')
Shipping Fee:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_shipping_fee }}<br>
@endif
Total:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_total }}<br>


@component('mail::button', ['url' => $url])
Order Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
