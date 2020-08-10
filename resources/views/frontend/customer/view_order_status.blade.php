@extends('frontend.frontend_template')

@section('store_header')
@include('frontend.customer.navbar_myaccount')
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Order Status</li>
			  </ol>
			</nav>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			@include('frontend.customer.list_bar')
		</div>
		<div class="col-md-9 mb-5">
			<h2 class="mb-4">Order Status</h2>
			<div>
				<table class="table table-condensed table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>Order #</th>
							<th>Date Order</th>
							<th>Status</th>
							<th>Qty</th>
							<th>Total</th>
							<th>Details</th>
						</tr>
					</thead>
					<tbody>
						@if (count($order_statuses) > 0)
							@foreach ($order_statuses as $order)
							<tr>
								<td>{{$order->number}}</td>
								<td>{{$order->order_created}}</td>
								<td>{{$order->order_status}}</td>
								<td>{{$order->order_quantity}}</td>
								<td>&#8369;{{$order->order_total}}</td>
								<td><a href="/order/{{$order->number}}/details" class="btn btn-primary btn-sm">View</a></td>
							</tr>
							@endforeach
						@else
							<tr>
								<td colspan="6" align="center">No order yet.</td>
							</tr>
						@endif
					</tbody>
				</table>
				{{$order_statuses->links()}}
			</div>
		</div>
		{{-- <order-status :customer="{{ Auth::guard('customer')->user() }}"></order-status> --}}
	</div>

</div>
@endsection