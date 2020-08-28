@extends('frontend.frontend_template')

@section('store_header')
	@include('frontend.customer.navbar_myaccount')
@endsection

@section('content')
<div class="container mb-5">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="{{ route('customer.orders') }}">My Orders</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Replacements</li>
			  </ol>
			</nav>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-3">
			@include('frontend.customer.list_bar')
		</div>
		<div class="col-md-9">
			<h3 class="mb-4">Replacements</h3>
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>Details</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					@if (count($replacements) > 0)
						@foreach ($replacements as $request)
						<tr>
							<td>
								<div class="media">
	                        <img src="/storage/products/{{$request->inventory->product->product_image}}" class="media-object mr-2" width="16%" height="10%" alt="product image">
	                        <div class="media-body">
	                        	<span class="d-block"><b>ID:</b> {{$request->id}}</span>
	                        	<span class="d-block"><b>Order Number</b>: <a href="{{route('customer.view_order', ['order'=>$request->order_number])}}">{{$request->order_number}}</a></span>
	                           <span class="d-block"><b>Product:</b> {{ $request->product_name }}</span>
										<span class="d-block"><b>Quantity:</b> {{ $request->quantity }}</span>
										<span class="d-block"><b>Created:</b> {{ $request->request_created }}</span>
										<span class="d-block text-justify"><b>Reason:</b> {{ $request->reason }}</span>
	                        </div>
	                     </div>
							</td>
							<td>{{$request->status}}</td>
						</tr>
						@endforeach
					@else
						<tr>
							<td colspan="6" class="text-center">
								No replacements.
							</td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>

</div>
@endsection