@component('mail::message')
# Hello {{ $order->customer->first_name }},

We received your payment for Order #{{ $order->number }} placed on {{ $order->order_created }}.

Your order is now being processed. Your order will be delivered in {{ $date['days'] }} business days. Estimated delivery date {{ $date['date'] }}.

Once we've delivered your order, we'll send you another email with updated delivery details.

@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($order->orderProducts as $product)
|{{ $product->product_name }}|&#8369;{{ $product->price }}|{{ $product->quantity }}|&#8369;{{ $product->total }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_subtotal }}<br>
@if ($order->order_shipping_method == 'Shipping')
Delivery fee:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_shipping_fee }}<br>
@endif
Total:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_total }}<br>

You can check the status of your whole order and can print your invoice by visiting the Order Details page.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
