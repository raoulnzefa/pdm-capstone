@extends('frontend.frontend_template')

@section('content')
<div class="container">
		<div class="row">
			<div class="col">
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
				    <li class="breadcrumb-item active" aria-current="page">Login</li>
				  </ol>
				</nav>
			</div>	
		</div><!-- row 1 -->
		@if ($message = Session::get('status'))
		<div class="row">
			<div class="col">
				<div class="alert alert-success">
					<p class="m-0">{{ $message }}</p>
				</div>
			</div>	
		</div>
		@endif
		@if ($message = Session::get('cust_login_failed'))
		<div class="row mb-4">
			<div class="col">
				<div class="alert alert-danger">
					<p class="m-0"></i>{{ $message }}</p>
				</div>
			</div>	
		</div>
		@endif
		
		<customer-login :old_email="{{ json_encode(old('email')) }}" create_account="{{ url(route('customer_registration')) }}"></customer-login>	
</div>
@endsection
