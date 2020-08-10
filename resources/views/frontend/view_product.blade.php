@extends('frontend.frontend_template')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col">
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
				    <li class="breadcrumb-item"><a href="/products">Products</a></li>
				    <li class="breadcrumb-item active" aria-current="page">{{ $prod->name }}</li>
				  </ol>
				</nav>
			</div>	
		</div><!-- row 1 -->
	@guest('customer')
	<div class="row">
		<div class="col">
			<div class="alert alert-info">
				<h3><b>Save time later.</b></h3>
				<p class="mb-0">Create an account for fast checkout and easy access for order history. <a href="/register"><b>Register here</b></a></p>
			</div>
		</div>	
	</div><!-- row 1 -->
	@endif
		<div class="page-wrapper">
			<div class="row product-frame mb-4">
				<div class="col-sm-6">
					<div>
						<img src="{{ asset('storage/products/'.$prod->image) }}" class="img-fluid" width="80%">	
					</div>
				</div>

				<div class="col-sm-6">
					<view-product :customer="{{ Auth::guard('customer')->user() }}" :product="{{ $prod }}" :cart="{{ $cart }}"></view-product>
				</div><!-- col-md-6 -->
			</div><!-- row1 -->			
			
			<related-products :customer="{{ Auth::guard('customer')->user() }}" :prod="{{ $prod }}"></related-products>
			
		</div><!-- page-wrapper -->
	</div><!-- container -->
@endsection