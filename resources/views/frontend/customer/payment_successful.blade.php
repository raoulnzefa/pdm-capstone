@extends('frontend.frontend_template')

@section('store_header')
	@include('frontend.customer.navbar_myaccount')
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-12">

			@if (session()->has('state_error'))
			<div class="alert alert-danger">
				{{ session()->get('state_error') }}
				{{ session()->forget('state_error') }}
			</div>
			@endif
			@if ($order = Session::get('order'))
			<div class="mt-5 mb-4">
				<h3 style="font-weight: bolder; font-size: 50px;" class="mb-2"><i class="fa fa-check-circle text-success"></i>&nbsp;Thank you!</h3>
				<h4>Your order has been placed successfully.</h4>
			</div>
			{{-- <i class="fa fa-check-circle-o text-success" style="font-size: 50px;"></i> --}}
			<div>
				<p class="mb-0">Your order number is: <b>{{ $order->number }}</b></p>
				{{-- @if ($order->status != 'Pending Payment')
					<p class="mb-0">You can print your invoice in your order details page.</p>
				@endif --}}
				<p>Thank you for shopping with us.</p>
				{{-- <a href="/products" class="btn btn-dark ifg-btn"><i class="fa fa-chevron-left"></i>&nbsp;Continue Shopping</a> --}}
				<a href="/order/{{ $order->number }}/details" class="btn btn-dark ifg-btn"><i class="fa fa-chevron-left"></i>&nbsp;View Order Details</a>
			</div>
			{{ Session::forget(['order','payment_success']) }}
			@endif

		</div>
	</div>		
</div>
@endsection