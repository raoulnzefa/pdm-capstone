@extends('frontend.frontend_template')

@section('content')
<div class="container">
	<div class="row">
		<div class="col">
			<nav aria-label="breadcrumb">
			  	<ol class="breadcrumb">
			    	<li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
			   	<li class="breadcrumb-item active" aria-current="page">Search result: "{{ $search_data }}"</li>
			  	</ol>
			</nav>
		</div>	
	</div><!-- row 1 -->
	<div class="mt-2">
		<div class="row">
			<div class="col-md-12">
				@if (count($search_result) > 0)
					<h2 class="text-center mb-5"><i class="fa fa-search text-success"></i>&nbsp;Search result for: "{{ $search_data }}"</h2>
					<div class="row justify-content-center">
						@foreach ($search_result as $product)
						<div class="col-md-3">
							<div class="card">
								<a href="/product/view/{{ $product->url }}" class="product-link">
									<img src="/storage/products/{{ $product->image}}" class="img-fluid card-img-top" alt="" width="500" height="500">
								</a>
								<div class="card-body">
									<h6 class="text-center">{{  $product->name }}</h6>
					
									<div class="text-center">
										@if ($product->has_variant == 'No')
											@if (!$product->discount)
												<h6>PHP {{ $product->productNoVariant->price }}</h6>
											@else
												<h6><s class="text-secondary">PHP {{ $product->productNoVariant->price }}</s> PHP {{ $product->productNoVariant->discounted_price }}</h6>
											@endif
											@auth('customer')
												<add-cart-page :customer="{{ Auth::guard('customer')->user() }}" :product="{{ $product }}"></add-cart-page>
											@endif
										@else
											@auth('customer')
												<a href="/product/view/{{ $product->url}}" class="btn btn-dark ifg-btn mt-2">CHOOSE OPTION</a>
											@endif
										@endif
									</div>
					
								</div>
							</div>
						</div>
						@endforeach
					</div>
				@else
					<br>
					<br>
					<h2 class="text-center"><i class="fa fa-times-circle text-danger"></i>&nbsp;Sorry, we couldn't find any results matching for: "{{ $search_data }}"</h2>
				@endif
			</div>
		</div><!-- row 2 -->
	</div><!-- product-wrapper -->
</div><!-- container1 -->
@endsection