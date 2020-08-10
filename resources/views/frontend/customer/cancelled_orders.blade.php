@extends('frontend.frontend_template')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Cancelled Orders</li>
			  </ol>
			</nav>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-12">
			<h2 class="ifg-header mb-3">Cancelled Orders</h2>
		</div>
	</div>
	<div class="row">
		<div class="col-3">
			@include('frontend.customer.list_bar')
		</div>
		<div class="col-md-9">
			<customer-cancelled-orders :customer="{{ Auth::guard('customer')->user() }}"></customer-cancelled-orders>
		</div>
	</div>

</div>
@endsection