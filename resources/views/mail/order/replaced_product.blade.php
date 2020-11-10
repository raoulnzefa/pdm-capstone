@component('mail::message')
# Dear {{ $replacement->customer->first_name }},

Greetings from Admin!

The item you have request for replacement has been replaced in your Order #{{ $replacement->order_number }}.

Item: {{ $replacement->product_name }}

Replaced Quantity: {{ $replacement->quantity }}

Replacement ID: {{ $replacement->id }}


Thanks,<br>
{{ $company->name }}
@endcomponent
