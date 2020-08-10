@component('mail::message')
# Dear {{ $cancellation->customer->first_name }}

Your cancellation request has been successfully approved.

This is your approved cancellation notification for your cancellation request on {{$cancellation->date_request }}, Order Number {{$cancellation->order_number}} placed on {{$cancellation->order->date_order}}.

# Cancellation Details

Reason: {{$cancellation->reason->title}}

@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($cancellation->order->orderProducts as $product)
|{{ $product->orderProduct->product_name }}|&#8369;{{ $product->orderProduct->price }}|{{ $product->quantity }}|&#8369;{{ $product->amount }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ number_format($cancellation->order->subtotal, 2) }}<br>
Shipping Fee:&nbsp;&nbsp;&nbsp;&#8369;{{ number_format($cancellation->order->shipping_cost, 2) }}<br>
Total:&nbsp;&nbsp;&nbsp;&#8369;{{ number_format($cancellation->order->total, 2) }}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
