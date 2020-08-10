@component('mail::message')
# Dear {{ $cancellation->customer->first_name }},

{{$msg}}

# Cancellation Details

Order Number: {{$cancellation->order_number}}<br>
Date Order: {{$cancellation->order->date_order}}<br>
Reason: {{$cancellation->reason->title}}

@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($cancellation->order->orderProducts as $product)
|{{ $product->product_name }}|&#8369;{{ $product->price }}|{{ $product->quantity }}|&#8369;{{ $product->total }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ number_format($cancellation->order->subtotal, 2) }}<br>
Shipping Fee:&nbsp;&nbsp;&nbsp;&#8369;{{ number_format($cancellation->order->shipping_cost, 2) }}<br>
Total:&nbsp;&nbsp;&nbsp;&#8369;{{ number_format($cancellation->order->total, 2) }}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
