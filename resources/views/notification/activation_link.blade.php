@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => route('frontend_homepage')])
            {{ $company->name }}
        @endcomponent
    @endslot
{{-- Body --}}
    #Hi {{$customer->first_name}}.

    Thank you for creating your account at {{$company->name}}.

    To verify your email please click the button below.
    
    @component('mail::button', ['url' => $url])
    Verify Email
    @endcomponent

    Thanks,<br>
    {{ $company->name }}
{{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ $company->name }}
        @endcomponent
    @endslot
@endcomponent
