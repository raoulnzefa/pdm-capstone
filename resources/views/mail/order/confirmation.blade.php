@component('mail::message')
# Hello {{ $order->customer->first_name }},

# Thank you for your order.

Your order is being processed.
This is your order confirmation for Order #{{ $order->number }} placed on {{ $order->order_created }}.<br>

@if ($order->order_shipping_method == 'Shipping')
Your order will be ship in {{ $date['days'] }} business days. Estimated delivery date {{ $date['date'] }}.<br>
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
Discount:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_discount }}<br>
@if ($order->order_shipping_method == 'Shipping')
Shipping fee:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_shipping_fee }}<br>
@endif
Total:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_total }}<br>


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
