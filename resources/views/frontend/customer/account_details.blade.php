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
			    <li class="breadcrumb-item active" aria-current="page">Account Details</li>
			  </ol>
			</nav>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-3">
			@include('frontend.customer.list_bar')
		</div>
		<div class="col-md-9">
			<account-details-form :cust="{{ Auth::guard('customer')->user() }}"></account-details-form>
		</div>
	</div>

</div>
@endsection