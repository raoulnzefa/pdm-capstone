@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => route('frontend_homepage')])
            {{ config('app.name') }}
        @endcomponent
    @endslot
{{-- Body --}}
    This is our main message {{ $url }}
{{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset
{{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}
        @endcomponent
    @endslot
@endcomponent
