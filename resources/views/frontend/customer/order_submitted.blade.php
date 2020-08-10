@extends('frontend.frontend_template')

@section('store_header')
	@include('frontend.customer.navbar_myaccount')
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-12">
			<div class="text-center mt-5">
				<i class="fa fa-check-circle-o text-success" style="font-size: 90px;"></i>
				<h3 style="font-weight: bolder; font-size: 55px;">Thank you!</h3>
				<h4>Your order has been successfully submitted.HEY</h4>
			</div>
		</div>
	</div>		
</div>
@endsection