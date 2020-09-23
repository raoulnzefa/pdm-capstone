@component('mail::message')
# Dear {{ $cancellation->customer->first_name }},

{{$msg}}

# Cancellation Details

Order Number: {{$cancellation->order_number}}<br>
Date Order: {{$cancellation->order->order_created}}<br>
Reason: {{$cancellation->reason->title}}

@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($cancellation->order->orderProducts as $product)
|{{ $product->product_name }}|&#8369;{{ $product->price }}|{{ $product->quantity }}|&#8369;{{ $product->total }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ number_format($cancellation->order->order_subtotal, 2) }}<br>
Discount:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_discount }}<br>
@if ($cancellation->order->order_shipping_method == 'Shipping')
Shipping fee:&nbsp;&nbsp;&nbsp;&#8369;{{ number_format($cancellation->order->order_shipping_fee, 2) }}<br>
@endif
Total:&nbsp;&nbsp;&nbsp;&#8369;{{ number_format($cancellation->order->order_total, 2) }}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
