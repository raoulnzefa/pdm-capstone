@component('mail::message')
# Dear {{ $cancellation->customer->first_name }}

Your cancellation request has been successfully approved.

This is your approved cancellation notification for your cancellation request requested on {{$cancellation->date_request }}, Order Number {{$cancellation->order_number}} placed on {{$cancellation->order->order_created}}.

# Cancellation Details

Reason: {{$cancellation->reason->title}}

@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($cancellation->order->orderProducts as $product)
|{{ $product->product_name }}|&#8369;{{ $product->price }}|{{ $product->quantity }}|&#8369;{{ $product->total }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ number_format($cancellation->order->order_subtotal, 2) }}<br>
@if ($cancellation->order->order_shipping_method == 'Shipping')
Shipping fee:&nbsp;&nbsp;&nbsp;&#8369;{{ number_format($cancellation->order->order_shipping_fee, 2) }}<br>
@endif
Total:&nbsp;&nbsp;&nbsp;&#8369;{{ number_format($cancellation->order->order_total, 2) }}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
