@component('mail::message')
# Dear {{ $cancellation->customer->first_name }}

This is an update on your Order Number {{ $cancellation->order_number }} placed on {{$cancellation->order->date_order}}.

We are pleased to tell you that your refund has been successfully created. The refund is manually issued in {{$cancellation->order->payment_method}}.

You can check the status of your cancellation request by visiting the Cancellation page.


# Cancellation Details

Reason: {{$cancellation->reason->title}}

@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($cancellation->cancelProductRequests as $product)
|{{ $product->orderProduct->product_name }}|&#8369;{{ $product->orderProduct->price }}|{{ $product->quantity }}|&#8369;{{ $product->amount }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ $amount_detail['subtotal'] }}<br>
Shipping Fee:&nbsp;&nbsp;&nbsp;&#8369;{{  $amount_detail['shipping_fee'] }}<br>
Total:&nbsp;&nbsp;&nbsp;&#8369;{{  $amount_detail['total'] }}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
