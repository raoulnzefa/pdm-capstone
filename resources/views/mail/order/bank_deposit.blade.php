@component('mail::message')
# Hello {{ $order->customer->first_name }},

Thank you for your order.

Your order has been placed successfully. Your order details are shown below for you reference.

Make your payment directly into our bank account. Kindly use your Order #{{ $order->number }} as the payment reference.

Your order has been reserved within {{ $due_date['days'] }} business days until {{ $due_date['date'] }}.

Once your payment has been received we will start processing your order.
 
# Details
<ul>
	<li>Bank Name: <b>{{ $bank_account->bank_name }}</b></li>
	<li>Account Name: <b>{{ $bank_account->first_name.' '.$bank_account->last_name }}</b></li>
	<li>Account Number: <b>{{ $bank_account->number }}</b></li>
</ul>

# Order #: {{ $order->number }}
# Placed on: {{ $order->order_created }}
# Total Payment: &#8369;{{ $order->order_total }}

@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($order->orderProducts as $product)
|{{ $product->product_name }}|&#8369;{{ $product->price }}|{{ $product->quantity }}|&#8369;{{ $product->total }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_subtotal }}<br>
@if ($order->order_delivery_method == 'Delivery')
Shipping Fee:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->delivery->delivery_fee }}<br>
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
