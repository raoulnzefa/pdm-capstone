@component('mail::message')
# Dear {{ $replacement->customer->first_name }},



Thanks,<br>
{{ config('app.name') }}
@endcomponent
