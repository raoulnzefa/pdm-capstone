@component('mail::message')
# Hello {{ $order->customer->first_name }},

Your order has been picked up. Order Number {{ $order->number }}.

This email is to confirm that the products below were picked up on {{ $order->date_completed }} at Bunlo, Mac Arthur Hi-way, 2500 Bocaue, Bulacan.

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

We hope you enjoy your purchase! Thank you for shopping with us.

Sincerely,<br>
{{ config('app.name') }}
@endcomponent
