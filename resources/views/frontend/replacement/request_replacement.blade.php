@extends('frontend.frontend_template')

@section('store_header')
	@include('frontend.customer.navbar_myaccount')
@endsection

@section('content')
<div class="container mb-5">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="{{ route('customer.orders') }}">My Orders</a></li>
			    <li class="breadcrumb-item"><a href="{{ route('customer.view_order',['order'=>$order->number]) }}">Order No. {{ $order->number }}</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Request Replacement</li>
			  </ol>
			</nav>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-3">
			@include('frontend.customer.list_bar')
		</div>
		<div class="col-md-9">
			<div class="card">
				<div class="card-header">
					<h3 class="mb-0">Request Replacement</h3>
				</div>
				<div class="card-body">
					<request-replacement :order="{{$order}}" :customer="{{Auth::guard('customer')->user()}}"></request-replacement>
				</div>
			</div>
		</div>
	</div>

</div>
@endsection