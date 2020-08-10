@component('mail::message')
# Dear {{ $return_detail->customer->first_name }},

We are pleased to inform you that the status of your return request for replacement for the following item in your order {{ $return_detail->order_number }} has been by the store owner.

# Return ID: {{ $return_detail->id }}

@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($return_detail->returnProductRequests as $product)
|{{ $product->orderProduct->product_name }}|&#8369;{{ $product->orderProduct->price }}|{{ $product->quantity }}|&#8369;{{ $product->amount }}
@endforeach

@endcomponent

Return Status has been changed to <b>Completed</b>.

You can check the status of your whole order and can print your invoice by visiting the Order Details page.

To know more about the return request, click the button below to redirect you to return section page.

@component('mail::button', ['url' => $url])
Return List
@endcomponent


Thanks,<br>
{{ config('app.name') }}
@endcomponent
