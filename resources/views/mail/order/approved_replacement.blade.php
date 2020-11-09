@component('mail::message')
# Dear {{ $replacement->customer->first_name }},

Greetings from Admin!

Your replacement request has been approved for the following item in your Order #{{ $replacement->order_number }}.

Item: {{ $replacement->product_name }}

Replacement ID for this request is: {{ $replacement->id }}

The store owner will now take further actions on this replacement request. You can track the status of your replacement request in the replacements page in customer page.

We apologize for any inconvenience caused to you.


Thanks,<br>
{{ $company->name }}
@endcomponent
