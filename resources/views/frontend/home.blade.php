@extends('frontend.frontend_template')

@section('content')
<div class="">
	<div class="row">
		<div class="col-md-12">
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
			<div>
				<h2 class="mt-4 text-center">Our Products</h2>
					@if (count($products) > 0)
					<div class="row">
						@foreach ($products as $product)
							<div class="col-md-3 d-flex align-items-stretch">
								<div class="card">
				            	<a href="{{ route('customer_view_product', ['category'=>$product->category->url, 'product'=>$product->product_url]) }}"><img class="card-img-top" src="{{ $product->product_image_url }}" alt="{{ $product->produc_name }}"></a>
				              	<div class="card-body d-flex flex-column">
				               	<span class="card-title text-center prod-name-link">
				               		<a href="{{ route('customer_view_product', ['category'=>$product->category->url, 'product'=>$product->product_name]) }}">{{ $product->product_name }}</a>
				               	</span>
				                	<div class="text-center mb-3">
				                		<input type="hidden" name="p_num" id="p_num"  value="{{$product->number}}">
				                		@if ($product->product_has_variant < 1)
					                		<span class="cart-prod-price">&#8369;{{ number_format($product->productNoVariant->price,2) }}</span>
					                	@elseif (!is_null($product->productWithVariants))
					                		@foreach ($product->productWithVariants as $variant)
					                			@if($loop->first) 
					                				<span class="cart-prod-price">&#8369;{{number_format($variant->variant_price,2)
					                				}} -</span>
					                			@elseif($loop->last)
					                				<span class="cart-prod-price">&#8369;{{number_format($variant->variant_price,2)
					                				}}</span>
					                			@endif
					                		@endforeach
					                	@endif
				                	</div>
				                	@auth('customer')
				                		<a href="{{ route('customer_view_product', ['category' => $product->category->url, 'product'=> $product->product_url]) }}" class="btn btn-primary mt-auto">View</a>
				                	@endauth
				              	</div>
				            </div>
							</div><!-- col-md-4 -->
						@endforeach
					</div>
					<center>
						<a href="/products" class="btn btn-primary mb-4">View more</a>
					</center>
				@else
					<div class="alert alert-warning text-center">
						No products.
					</div>
				@endif
			</div>
		</div>
	</div><!-- row -->
</div> {{-- carousel --}}
@endsection