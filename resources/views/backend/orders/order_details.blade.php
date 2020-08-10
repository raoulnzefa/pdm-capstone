@extends('backend.backend_template')

@section('content')
<div class="row justify-content-center">
	<div class="col-md-9">
		<div class="clearfix mb-2">
			<h3 class="float-left">Order Details</h3>
			<a href="{{ route('orders') }}" class="btn btn-secondary float-right">Back</a>
		</div>
		<div class="card mb-5">
			<div class="card-body pt-4">
				@if ($order->order_delivery_method == 'Delivery')
					@if (($order->order_status == 'Pending payment') && ($order->order_payment_method == 'Bank Deposit'))
					<div class="clearfix">
						<h4 class="float-left">Order #{{$order->number}}</h4>
						<view-bank-deposit-slip 
							:order="{{$order}}"
							:admin="{{ Auth::guard('admin')->user() }}"></view-bank-deposit-slip>
					</div>
					@elseif (($order->order_status == 'Processing') && ($order->order_payment_method == 'Bank Deposit'))
						<div class="clearfix">
							<h4 class="float-left">Order #{{$order->number}}</h4>
							<deliver-order
								:order="{{$order}}"
								:admin="{{ Auth::guard('admin')->user() }}">
							</deliver-order>
						</div>
					@elseif (($order->order_status == 'Processing') && ($order->order_payment_method == 'PayPal'))
						<div class="clearfix">
							<h4 class="float-left">Order #{{$order->number}}</h4>
							<deliver-order
								:order="{{$order}}"
								:admin="{{ Auth::guard('admin')->user() }}">
							</deliver-order>
						</div>
					@elseif ($order->order_status == 'Delivered')
						<div class="clearfix">
							<h4 class="float-left">Order #{{$order->number}}</h4>
							<mark-as-completed
								:order="{{$order}}"
								:admin="{{ Auth::guard('admin')->user() }}">
							</mark-as-completed>
						</div>
					@endif
				@else
					@if ($order->order_status == 'For pickup')
						<div class="clearfix">
							<h4 class="float-left">Order #{{$order->number}}</h4>
							<picked-up-order
								:order="{{$order}}"
								:admin="{{ Auth::guard('admin')->user() }}"></picked-up-order>
						</div>
					@endif
				@endif
				@if ($order->order_status == 'Cancelled')
					<div class="clearfix">
						<h4 class="float-left">Order #{{$order->number}}</h4>
						<!-- Invoice -->
					</div>
				@elseif ($order->order_status == 'Completed')
					<div class="clearfix">
						<h4 class="float-left">Order #{{$order->number}}</h4>
						<!-- Invoice -->
					</div>
				@endif
				<!-- /header card --> 
				<div class="row mt-3">
					<div class="col-md-6">
						<table class="table order-table">
							<tr>
								<td width="40%">Placed on:</td>
								<td>{{ $order->order_created }}</td>
							</tr>
							<tr>
								<td>Total:</td>
								<td>&#8369;{{ $order->order_total }}</td>
							</tr>
							<tr>
								<td>Delivery Method:</td>
								<td>{{ $order->order_delivery_method }}</td>
							</tr>
							<tr>
								<td>Payment Method:</td>
								<td>{{ $order->order_payment_method }}</td>
							</tr>
							<tr>
								<td>Payment Status:</td>
								<td>{{ $order->order_payment_status }}</td>
							</tr>
							<tr>
								<td>Status:</td>
								<td>{{ $order->order_status }}</td>
							</tr>
							@if ($order->order_status == 'Delivered' || $order->order_status == "Completed")
							<tr>
								<td>Warranty date:</td>
								<td>{{ $order->order_warranty }}</td>
							</tr>
							@endif
						</table>
					</div>
					<div class="col-md-6">
						@if ($order->order_delivery_method == "Delivery")
						<table class="table order-table">
							<tr>
								<th class="text-right">Delivery Address</th>
							</tr>
							<tr>
								<td align="right">{{ $order->delivery->delivery_firstname.' 
								 '.$order->delivery->delivery_lastname }}</td>
							</tr>
							<tr>
								
								<td align="right">{{ $order->delivery->delivery_street }}</td>
							</tr>
							<tr>
								
								<td align="right">{{ $order->delivery->delivery_barangay.', '.$order->delivery->delivery_municipality.', '.$order->delivery->delivery_province.', '.$order->delivery->delivery_zip_code }}</td>
							</tr>
							<tr>
								<td align="right">{{ $order->delivery->delivery_mobile_no }}</td>
							</tr>
						</table>
						@endif
					</div>
				</div><!-- row -->
				<div class="mt-4">
					<h5 class="mb-3">Ordered Products</h5>
					<table class="table table-striped mb-0">
						<thead>
							<tr>
								<th width="50%">Product(s)</th>
								<th>Price</th>
								<th>Quantity</th>
								<th>Amount</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($order->orderProducts as $orderProd)
							<tr>
								<td>
									<img src="/storage/products/{{$orderProd->inventory->product->image}}" class="img-fluid" width="20%" height="13%">
									<span class="align-middle">{{ $orderProd->product_name }}</span>
								</td>
								<td class="align-middle">&#8369;{{ $orderProd->price }}</td>
								<td class="align-middle">{{ $orderProd->quantity }}</td>
								<td class="align-middle">&#8369;{{ $orderProd->total }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					<table class="table mb-4">
						<tr>
							<th class="text-right">Subtotal:</th>
							<td width="30%" class="text-right">&#8369;{{ $order->order_subtotal }}</td>
						</tr>
						@if ($order->order_delivery_method == 'Delivery')
						<tr>
							<th class="text-right">Delivery fee:</th>
							<td class="text-right">&#8369;{{ $order->delivery->delivery_fee }}</td>
						</tr>
						@endif
						<tr>
							<th class="text-right">Total:</th>
							<td class="text-right">&#8369;{{ $order->order_total }}</td>
						</tr>
					</table>
				</div>
			</div>
		</div><!-- card -->
	</div>
</div>
{{-- <order-details 
	id="{{ $order->number }}" 
	:ord_data="{{$order}}" 
	previous_url="{{ $previous_url }}" 
	order_num="{{$order_num}}" 
	:admin="{{ Auth::guard('admin')->user() }}">
</order-details> --}}
@endsection