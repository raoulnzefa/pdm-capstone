@extends('frontend.frontend_template')

@section('content')
<div class="container-fluid">
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
			<div class="col-md-2 col-lg-2">
				@include('frontend.partial.category_sidebar')
			</div>
			<div class="col-md-10 col-lg-10">
				<h2 class="ifg-header mb-3">Products</h2>
				@if (count($products) > 0)
					<div class="row">
						@foreach ($products as $product)
							<div class="col-md-3 d-flex align-items-stretch">
								<div class="card">
				            	<a href="{{ route('customer_view_product', ['category'=>$product->category->url, 'product'=>$product->product_url]) }}"><img class="card-img-top" src="{{ url('storage/products/'.$product->product_image)}}" alt="{{ $product->produc_name }}"></a>
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

@section('postJquery')
<script type="text/javascript">
	$(document).ready(function() {
		$('.addProdToCart').click(function() {
			var prod_num = $('#p_num').val();

			$.ajax({
				url: 'cart/add',
				method: 'post',
				data: {
					_token: '{{ csrf_token() }}',
					product_number: prod_num,
					quantity: 1,
				},
				success: function(response) {
					console.log(response.data);
				}
			});
		});
	});
</script>
@endsection