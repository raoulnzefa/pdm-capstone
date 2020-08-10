@extends('frontend.frontend_template')

@section('store_header')
@include('frontend.customer.navbar_myaccount')
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
			    <li class="breadcrumb-item active" aria-current="page">My Account</li>
			  </ol>
			</nav>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-3">
			@include('frontend.customer.list_bar')
		</div>
		<div class="col-9">
			<h2 class="">My Account</h2>
			<p>My Account page shows the details of your order and account.</p>
			
			<div class="mt-5">
				
				<p><a href="{{ route('customer.order_status') }}">Order Status</a> - Shows the order details of your recent order status </p>
				<p><a href="{{ route('customer.order_history') }}">Order History</a> - Shows the order details of completed, refunded, and cancelled order.
				</p>
				<p><a href="{{ route('customer.cancellation') }}">Cancellation</a> - Shows the Cancelation Request Status of your cancelled order.
				</p>
				<p><a href="{{ route('customer.return_requests') }}">Return</a> - Shows the Return Request Status of your returned order.
				</p>
				<p><a href="{{ route('customer.account_details') }}">Account Details</a> - Shows the Account Details page where you can update your account information.
				</p>
				<p><a href="{{ route('customer_address') }}">Addresses</a> - Shows the Addresses page where you can update your address.
				</p>
			</div>
		</div>
	</div>
		
	
</div>
@endsection