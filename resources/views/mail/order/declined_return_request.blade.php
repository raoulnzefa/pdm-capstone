@component('mail::message')
# Dear {{$return_declined->customer->first_name}}

{{$msg}}

# Return Request Details

Order Number: {{ $return_declined->order_number }}<br>
Date Request: {{$return_declined->date_return_request}}<br>
Return Reason: {{$return_declined->reason->title}}

@component('mail::table')

| Product       | Price       | Qty 	 | Amount	   |
| ------------- |:-----------:| --------:|------------:|
@foreach ($return_declined->returnProductRequests as $product)
|{{ $product->orderProduct->product_name }}|&#8369;{{ $product->orderProduct->price }}|{{ $product->quantity }}|&#8369;{{ $product->amount }}
@endforeach

@endcomponent

Subtotal:&nbsp;&nbsp;&nbsp;&#8369;{{ number_format($return_declined->subtotal,2) }}<br>
Total:&nbsp;&nbsp;&nbsp;&#8369;{{  number_format($return_declined->total,2) }}<br>

@component('mail::button', ['url' => $url])
Return Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
