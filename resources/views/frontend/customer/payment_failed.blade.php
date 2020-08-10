@extends('frontend.frontend_template')

@section('store_header')
	@include('frontend.customer.navbar_myaccount')
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-12">
			<div class="text-center mt-5">
				<i class="fa fa-times-circle-o text-danger" style="font-size: 90px;"></i>
				<h3 style="font-weight: bolder; font-size: 45px;">Payment Failed.</h3>
				@if(session()->has('error'))
				    <div class="alert alert-danger">
				        {{ session()->get('error') }}
				    </div>
				@endif
			</div>
		</div>
	</div>		
</div>
@endsection