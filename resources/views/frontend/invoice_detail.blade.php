@extends('frontend.frontend_template')

@section('store_header')
<nav class="navbar navbar-light bg-dark" id="checkout-header">
    <div class="container">
        <div class="navbar-brand">
            <img src="{{ asset('images/logo.jpg') }}" width="160" height="70" alt="INFINITY FIGHTGEAR LOGO">
        </div>
    </div>
</nav>
@endsection

@section('content')
<div class="pt-4">
    <customer-invoice invoice="{{$invoice}}" previous_url="{{$previous_url}}"></customer-invoice>
</div>
@endsection

@section('footer')
<div class="row bg-dark">
	<div class="col-md-12">
		<div id="checkout-footer">
			<center class="">{{ config('app.name') }} &copy; {{ date('Y') }}</center>	
		</div>
		
	</div>
</div>
@endsection