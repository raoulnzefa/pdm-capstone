@component('mail::message')
# Hello {{ $order->customer->first_name }},

# Thank you for your order.

Your order is being processed.
This is your order confirmation for Order #{{ $order->number }} placed on {{ $order->order_created }}.<br>

@if ($order->order_delivery_method == 'Delivery')
Your order will be delivered in {{ $date['days'] }} business days. Estimated delivery date {{ $date['date'] }}.<br>
Once we've shipped your order, we'll send you another email with updated delivery details.
@else
You may pickup and pay your order in our store in Bunlo, Mac Arthur Hi-way, 2500 Bocaue, Bulacan.

Your order can be reserved within {{ $date['days'] }} business days until {{ $date['date'] }}.
@endif

@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($order->orderProducts as $product)
|{{ $product->product_name }}|&#8369;{{ $product->price }}|{{ $product->quantity }}|&#8369;{{ $product->total }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_subtotal }}<br>
@if ($order->order_delivery_method == 'Delivery')
Delivery fee:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->delivery->delivery_fee }}<br>
@endif
Total:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_total }}<br>


@component('mail::button', ['url' => $url])
Order Details
@endcomponent

@if ($order->order_delivery_method == 'Delivery')
Delivery information:

{{ $order->delivery->delivery_firstname.' '.$order->delivery->delivery_lastname }}<br>
{{ $order->delivery->delivery_street.', '.$order->delivery->delivery_barangay }}<br>
{{ $order->delivery->delivery_municipality.', '.$order->delivery->delivery_province.', '.$order->delivery->delivery_zip_code }}<br>
{{ $order->delivery->delivery_mobile_no }}
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
