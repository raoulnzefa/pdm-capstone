@extends('frontend.frontend_template')

@section('content')
<div class="">
	<div class="row">
		<div class="col">
			<div id="demo" class="carousel slide" data-ride="carousel">
			  	<!-- Indicators -->
			  	<ul class="carousel-indicators">
			    	<li data-target="#demo" data-slide-to="0" class="active"></li>
			    	<li data-target="#demo" data-slide-to="1"></li>
			  	</ul>

			  	<!-- The slideshow -->
			  	<div class="carousel-inner">
			    	<div class="carousel-item active">
			      		<img src="{{ asset('images/ADS.jpg') }}" alt="Slide 1">
			    	</div>
			    	<div class="carousel-item">
			      		<img src="{{ asset('images/ADS5.jpg') }}" alt="Slide 2">
			    	</div>
			    	
			  	</div>

			  	<!-- Left and right controls -->
			  	<a class="carousel-control-prev" href="#demo" data-slide="prev">
			    	<span class="carousel-control-prev-icon"></span>
			  	</a>
			  	<a class="carousel-control-next" href="#demo" data-slide="next">
			   		<span class="carousel-control-next-icon"></span>
			  	</a>
			</div>
		</div>
	</div><!-- row -->
</div> {{-- carousel --}}
<div class="container">
	<div class="product-wrapper">
		<div class="row mb-3 mt-3">
			<div class="col-md-12">
				<h2 class="mt-2 mb-2">Featured Products</h2>
				<!-- put view more at the bottom of featured products -->
				<!-- to redirect to featured page -->
				@if (count($featured_products) > 0)
					<div class="row">
						@foreach ($featured_products as $product)
							<div class="col-md-3">
								<div class="card">
									<a href="/product/view/{{$product->url}}" class="product-link">
										<img src="{{ asset('storage/products/'.$product->image)}}" class="img-fluid prod-img-frame card-img-top" alt="" width="500" height="500">
									</a>
									<div class="card-body">
										<h6 class="text-center">{{  $product->name }}</h6>
										@auth ('customer')
											<div class="text-center mt-4">
												@if ($product->has_variant == 'Yes')
													<a href="/product/view/{{ $product->url}}" class="btn btn-dark ifg-btn">CHOOSE OPTION</a>
												@else
													<add-cart-page :customer="{{ Auth::guard('customer')->user() }}" :product="{{ $product }}"></add-cart-page>
												@endif
											</div>
										@endauth
									</div>
								</div>
							</div><!-- col-md-4 -->
						@endforeach
					</div>
					<div class="row justify-content-center mb-2">
						<div class="co-md-3"><a href="/featured-products">View more</a></div>
					</div>
				@else
					<div class="alert alert-warning text-center">
						No featured products.
					</div>
				@endif
			</div>
		</div>
		
	</div><!-- product-wrapper -->
</div><!-- container --> 
{{-- featured products --}}
@endsection