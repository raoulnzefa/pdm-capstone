@component('mail::message')
# Introduction
# Pending Status

The body of your message.

@component('mail::button', ['url' => $paypal_redirect_url])
Proceed to PayPal
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
