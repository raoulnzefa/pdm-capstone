@extends('frontend.frontend_template')

@section('store_header')
<nav class="navbar navbar-expand-sm bg-dark navbar-dark" id="partial-header">
	<div class="container-fluid">
	 	<div class="navbar-brand">
      	<img src="{{ asset('images/logo.jpg') }}" width="125" height="60" alt="INFINITY FIGHTGEAR LOGO">
     	</div>
</nav>
@endsection

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-10">
			<center>
				<div class="mt-5">
					@if ($order = Session::get('order'))
					<h3 style="font-weight: bolder; font-size: 40px; margin-top: 100px;" class="mb-2"><i class="fa fa-check-circle text-success"></i>&nbsp;Thank you for shopping with us.</h3>
					<h3 style="font-weight: bolder; font-size:25px;" class="mb-3">Your order has been placed successfully.</h3>
					<div>
						<p  style="font-weight: bolder; font-size:25px;">You will receive a confirmation by email to verify your order.</p>
						<a href="/order/{{ $order->number }}/details" class="btn btn-success btn-lg">View Order Details</a>
					</div>
					@endif
				</div>
			</center>
		</div>
	</div>		
</div>
@endsection