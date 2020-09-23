@component('mail::message')
# Hello {{ $order->customer->first_name }},

Here is an update on your Order Number {{ $order->number }} placed on {{ $order->order_created }}.

We are please to tell you that this product(s) is now on its way to you.

You can check the status of your whole order by visiting the Order Details page.


@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($order->orderProducts as $product)
|{{ $product->product_name }}|&#8369;{{ $product->price }}|{{ $product->quantity }}|&#8369;{{ $product->total }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_subtotal }}<br>
Discount:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_discount }}<br>
Shipping Fee:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_shipping_fee }}<br>
Total:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_total }}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
