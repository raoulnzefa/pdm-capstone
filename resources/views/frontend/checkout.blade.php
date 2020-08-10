@extends('frontend.frontend_template')

@section('store_header')
<nav class="navbar navbar-light bg-dark" id="checkout-header">
    <div class="container-fluid">
        <div class="navbar-brand">
            <img src="{{ asset('images/logo.jpg') }}" width="125" height="60" alt="INFINITY FIGHTGEAR LOGO">
        </div>
    </div>
</nav>
@endsection
@section('content')
<div class="container">
    <checkout-page 
        :customer="{{$customer}}"
        :cart="{{$cart}}"
        :provinces="{{$provinces}}"
        :municipalities="{{ $municipalities }}"
        :barangays="{{ $barangays }}"
        :shipping_rate="{{json_encode($shipping_rate)}}"
        cart_subtotal="{{number_format($cart_subtotal,2)}}"
        ></checkout-page>
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