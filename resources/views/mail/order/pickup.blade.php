@component('mail::message')
# Hello {{ $order->customer->first_name }},

# Thank you for your order.

Your order is being processed. This is your order confirmation for Order Number {{ $order->number }} placed on {{ $order->order_created }}.<br>

You may pickup and pay your order in our store in Bunlo, Mac Arthur Hi-way, 2500 Bocaue, Bulacan.

Your order can be reserved within {{ $due_date['days'] }} business days until {{ $due_date['date'] }}.

@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($order->orderProducts as $product)
|{{ $product->product_name }}|&#8369;{{ $product->price }}|{{ $product->quantity }}|&#8369;{{ $product->total }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_subtotal }}<br>
Discount:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_discount }}<br>
Total:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_total }}<br>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
