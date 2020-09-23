@component('mail::message')
# Dear {{ $return_replacement->customer->first_name }},

Your return request for replacement on {{$return_replacement->date_return_request}} for Order Number {{$return_replacement->order_number}} has been successfully approved. For more details about return transaction please read our return and cancellation policy.

You may return the product directly to the shop or ship it back to us.
  
You will receive a replacement notification after we replaced your order.

# Return Request Details

Return Reason : {{$return_replacement->reason->title}}

@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($return_replacement->returnProductRequests as $product)
|{{ $product->orderProduct->product_name }}|&#8369;{{ $product->orderProduct->price }}|{{ $product->quantity }}|&#8369;{{ $product->amount }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ number_format($return_replacement->subtotal, 2) }}<br>
Discount:&nbsp;&nbsp;&nbsp;&#8369;{{ $order->order_discount }}<br>
Total:&nbsp;&nbsp;&nbsp;&#8369;{{ number_format($return_replacement->total, 2) }}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
