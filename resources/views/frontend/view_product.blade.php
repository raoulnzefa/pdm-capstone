@extends('frontend.frontend_template')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col">
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
				    <li class="breadcrumb-item"><a href="/products">Products</a></li>
				    <li class="breadcrumb-item"><a href="/products?ct={{$prod->category->url}}">{{ $prod->category->name }}</a></li>
				    <li class="breadcrumb-item active" aria-current="page">{{ $prod->product_name }}</li>
				  </ol>
				</nav>
			</div>	
		</div><!-- row 1 -->
		<div class="page-wrapper">
			@if ($prod->product_has_variant < 1)
				<view-product-no-variant 
				:product="{{$prod}}" 
				:customer="{{Auth::guard('customer')->user()}}"></view-product-no-variant>
			@else 
				<view-product-with-variants 
				:product="{{$prod}}" 
				:variant="{{$variant}}" 
				:product_variants="{{$product_variants}}"
				:customer="{{Auth::guard('customer')->user()}}"></view-product-with-variants>
			@endif
			
		</div><!-- page-wrapper -->
	</div><!-- container -->
@endsection