@component('mail::message')
# Dear {{$return_refund->customer->first_name}}

@if ($return_refund->order->payment_method == 'PayPal')
We want to inform you that your request for refund on {{$return_refund->date_return_request}} for Order number {{$return_refund->order_number}} was already sent to your Paypal account. Wait until three business days to check your Paypal account to claim your refund.
@elseif ($return_refund->order->payment_method == 'Bank Deposit')
We want to inform you that your request for refund on {{$return_refund->date_return_request}} for Order number {{$return_refund->order_number}} was already sent to your bank account. Wait until three business days to check your bank account to claim your refund.
@endif

# Return Request Details

Return Reason : {{ $return_refund->reason->title }}

@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($return_refund->returnProductRequests as $product)
|{{ $product->orderProduct->product_name }}|&#8369;{{ $product->orderProduct->price }}|{{ $product->quantity }}|&#8369;{{ $product->amount }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ $amount_detail['subtotal'] }}<br>
Shipping Fee:&nbsp;&nbsp;&nbsp;&#8369;{{  $amount_detail['shipping_fee'] }}<br>
Total:&nbsp;&nbsp;&nbsp;&#8369;{{  $amount_detail['total'] }}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
