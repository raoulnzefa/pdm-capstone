@component('mail::message')
# Dear {{ $replacement->customer->first_name }},

Greetings from Admin!

The item you hasve request for replacement has been replaced in your Order #{{ $replacement->order_number }}.

Item: {{ $replacement->product_name }}

Replacement ID for this request is: {{ $replacement->id }}


Thanks,<br>
{{ $company->name }}
@endcomponent
