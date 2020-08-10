@extends('frontend.frontend_template')

@section('store_header')
	@include('frontend.customer.navbar_myaccount')
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
			    <li class="breadcrumb-item"><a href="{{ route('customer.order.details',['order' => $order ]) }}">Order Details</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Invoice</li>
			  </ol>
			</nav>
		</div>
	</div>	
	
	<order-invoice :customer="{{ Auth::guard('customer')->user() }}" id="{{ $order->number }}"></order-invoice>
			
</div>
@endsection