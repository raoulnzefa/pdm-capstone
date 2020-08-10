@component('mail::message')
# Hello {{ $order->customer->first_name }},

Here is an update on your Order Number {{ $order->number }} placed on {{ $order->date_order }}.

We are please to tell you that this product(s) is now on its way to you.

You can check the status of your whole order by visiting the Order Details page.

@component('mail::button', ['url' => $url])
Order Details
@endcomponent

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

Thanks,<br>
{{ config('app.name') }}
@endcomponent
