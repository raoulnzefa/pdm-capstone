@component('mail::message')
# Hello {{ $order->customer->first_name }},

Here is an update on your Order #{{ $order->number }} placed on {{ strftime("%B %d, %Y", strtotime($order->order_created)) }}.

We are please to tell you that your order is now on its way to you.

You can check your order by visiting the Order Details page. Kindly click the button 'Receive order' if you already received your order.

Shipped: {{ date('F d, Y h:i A', strtotime($order->order_shipped)) }}.


Thanks,<br>
{{ $company->name }}
@endcomponent
