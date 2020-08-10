@extends('frontend.frontend_template')

@section('store_header')
	@include('frontend.customer.navbar_myaccount')
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-12">
			<div class="text-center mt-5">
				<h2>STATUS</h2>
				@if ($message = Session::get('success'))
				    <div class="alert alert-success">
			            {{ $message }}
					</div>
				<?php Session::forget('success');?>
				@endif	

				 @if ($message = Session::get('error'))
			        <div class="alert alert-danger">
			            {{  $message }}
			        </div>
				    <?php Session::forget('error');?>
				@endif

			</div>
		</div>
	</div>		
</div>
@endsection