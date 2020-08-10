@extends('frontend.frontend_template')

@section('content')
<div class="container">
	<div class="row">
		<div class="col">
			<nav aria-label="breadcrumb">
			  	<ol class="breadcrumb">
			    	<li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
			   	<li class="breadcrumb-item active" aria-current="page">Products</li>
			  	</ol>
			</nav>
		</div>	
	</div><!-- row 1 -->
	<div class="mt-2 mb-4">
		<div class="row">
			<div class="col-3">
				@include('frontend.partial.category_sidebar')
			</div>
			<div class="col-9">
				<h2 class="ifg-header mb-3">Products</h2>
				@if (count($products) > 0)
					<div class="row">
						@foreach ($products as $product)
							<div class="col-md-4">
								<div class="card">
									<a href="/product/view/{{$product->url}}" class="product-link">
										<img src="{{ asset('storage/products/'.$product->image)}}" class="img-fluid prod-img-frame card-img-top" alt="" width="500" height="500">
									</a>
									<div class="card-body">
										<h6 class="text-center">{{  $product->name }}</h6>
										@auth ('customer')
											<div class="text-center">
												@if ($product->has_variant == 'Yes')
													<a href="/product/view/{{ $product->url}}" class="btn btn-dark ifg-btn">CHOOSE OPTION</a>
												@else
													<h6 class="text-center">&#8369;{{$product->productNoVariant->price}}</h6>
													<add-cart-page :customer="{{ Auth::guard('customer')->user() }}" :product="{{ $product }}"></add-cart-page>
												@endif
											</div>
										@endauth
									</div>
								</div>
							</div><!-- col-md-4 -->
						@endforeach
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="row justify-content-center">
								<div class="col-md-4">
									{{ $products->links() }}
								</div>
							</div>
						</div>
					</div>
				@else
					<div class="alert alert-warning text-center">
						No products.
					</div>
				@endif
			</div>
		</div><!-- row 2 -->
	</div><!-- product-wrapper -->
</div><!-- container1 -->
@endsection