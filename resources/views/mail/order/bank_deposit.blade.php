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
Discount:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_discount}}<br>
@if ($order->order_shipping_method == 'Shipping')
Shipping Fee:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_shipping_fee }}<br>
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
{{ $company->name }}
@endcomponent
