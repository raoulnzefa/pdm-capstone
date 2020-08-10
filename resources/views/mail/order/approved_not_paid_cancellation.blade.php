@component('mail::message')
# Dear {{ $cancellation->customer->first_name }}

Your cancellation request has been successfully approved.

This is your approved cancellation notification for your cancellation request requested on {{$cancellation->date_request }}, Order Number {{$cancellation->order_number}} placed on {{$cancellation->order->date_order}}.

# Cancellation Details

Reason: {{$cancellation->reason->title}}

@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($cancellation->order->orderProducts as $product)
|{{ $product->product_name }}|&#8369;{{ $product->price }}|{{ $product->quantity }}|&#8369;{{ $product->total }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ $cancellation->order->subtotal }}<br>
Shipping Fee:&nbsp;&nbsp;&nbsp;&#8369;{{ $cancellation->order->shipping_cost }}<br>
Total:&nbsp;&nbsp;&nbsp;&#8369;{{ $cancellation->order->total }}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
