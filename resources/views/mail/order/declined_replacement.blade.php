@component('mail::message')
# Dear {{ $replacement->customer->first_name }},

Greetings from Admin!

Your replacement request has been declined for the following item in your Order #{{ $replacement->order_number }}

Item: {{ $replacement->product_name }}

Request Quantity: {{ $replacement->quantity }}

Replacement ID: {{ $replacement->id }}


We apologize for any inconvenience caused to you.

Thanks,<br>
{{ $company->name }}
@endcomponent
