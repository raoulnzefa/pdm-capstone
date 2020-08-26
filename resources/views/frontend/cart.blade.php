@extends('frontend.frontend_template')

@section('content')
	<div class="container mb-5">
		<div class="row">
			<div class="col">
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
				    <li class="breadcrumb-item active" aria-current="page">Cart</li>
				  </ol>
				</nav>
			</div>	
		</div><!-- row 1 -->
		@auth('customer')
			<cart :customer="{{ Auth::guard('customer')->user() }}"></cart>
		@else
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-warning text-center mt-3">
					Your cart is empty.
				</div>
			</div>	
		</div><!-- row 1 -->
		@endauth
	</div>{{-- .container --}}
@endsection