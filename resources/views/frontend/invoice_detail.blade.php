@extends('frontend.frontend_template')

@section('store_header')
<nav class="navbar navbar-expand-sm bg-dark navbar-dark" id="partial-header">
	<div class="container-fluid">
	 	<div class="navbar-brand">
      	<img src="{{ asset('images/logo.jpg') }}" width="125" height="60" alt="INFINITY FIGHTGEAR LOGO">
     	</div>
</nav>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12 col-lg-12">
		<div class="clearfix mb-2 mt-5">
			<view-invoice :invoice="{{$invoice}}"></view-invoice>
			<a href="{{ route('customer.invoice', ['order'=>$invoice->order_number]) }}" class="btn btn-secondary float-right">Back</a>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="clearfix" style="margin-bottom: 50px;">	
					<img src="/images/logo.jpg" class="float-left" width="200" height="100">
					
					<div style="width: 600px;" class="float-right">
						<table class="table order-table">
							<tr>
								<td align="right"><b>INFINITY FIGHTGEAR</b></td>
							</tr>
							<tr>
								<td align="right">Bunlo, Mac Arthur Hi-way, 2500 Bocaue, Bulacan</td>
							</tr>
							<tr>
								<td align="right">09987901118</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="clearfix mb-5">
					<div class="float-left" style="width: 25%;">
						<table class="table order-table">
							<tr>
								<th>Customer</th>
							</tr>
							<tr>
								<td></td>
							</tr>
							<tr>
								<td>
									{{ $invoice->first_name.' '.$invoice->last_name }}
								</td>
							</tr>
						</table>
					</div>
					@if ($invoice->order->order_shipping_method == 'Shipping')
					<div class="float-left" style="width:30%">
						<table class="table order-table">
							<tr>
								<th>Shipping Address</th>
							</tr>
							<tr>
								<td>{{ $invoice->order->shipping->shipping_firstname.' '.$invoice->order->shipping->shipping_lastname }}</td>
							</tr>
							<tr>
								<td>{{ $invoice->order->shipping->shipping_street }}</td>
							</tr>
							<tr>
								<td>{{ $invoice->order->shipping->shipping_barangay.', '.$invoice->order->shipping->shipping_municipal.', '.$invoice->order->shipping->shipping_province.', '.$invoice->order->shipping->shipping_zip_code }}</td>
							</tr>
							<tr>
								<td>{{ $invoice->order->shipping->shipping_mobile_no }}</td>
							</tr>
						</table>
					</div>
					@endif
					<div class="float-right" style="width: 35%;">
						<table class="table order-table">
							<tr>
								<th width="50%">Invoice Number:</th>
								<td class="text-right font-weight-bold" width="">{{ $invoice->number }}</td>
							</tr>
							<tr>
								<td>Invoice Date:</td>
								<td class="text-right">{{ $invoice->created }}</td>
							</tr>
							<tr>
								<td>Order Number:</td>
								<td class="text-right">{{ $invoice->order_number }}</td>
							</tr>
							<tr>
								<td>Shipping Method:</td>
								<td class="text-right">{{ $invoice->order->order_shipping_method }}</td>
							</tr>
							<tr>
								<td>Payment Method:</td>
								<td class="text-right">{{ $invoice->order->order_payment_method }}</td>
							</tr>
							<tr>
								<td>Total:</td>
								<td class="text-right">&#8369;{{ $invoice->total }}</td>
							</tr>
							@if ($invoice->status == 'Void')
							<tr>
								<td>Invoice Status:</td>
								<td class="text-right">{{ $invoice->status }}</td>
							</tr>
							@endif
						</table>

					</div>
				</div><!-- row -->
				<div class="mb-5 mt-4">
					<h4 class="mb-3">Ordered Products</h4>
					<table class="table table-bordered mb-0">
						<thead>
							<tr>
								<th>Product(s)</th>
								<th>Price</th>
								<th class="text-right">Quantity</th>
								<th class="text-right">Amount</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($invoice->invoiceProducts as $product)
							<tr>
								<td>{{ $product->product_name }}</td>
								<td>&#8369;{{ $product->price }}</td>
								<td align="right">{{ $product->quantity }}</td>
								<td align="right">&#8369;{{ $product->total }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					<table class="table">
						<tr>
							<th class="text-right">Subtotal:</th>
							<td width="25%" class="text-right">&#8369;{{ $invoice->subtotal }}</td>
						</tr>
						@if ($invoice->order->order_shipping_method == 'Shipping')
						<tr>
							<th class="text-right">Shipping fee:</th>
							<td class="text-right">&#8369;{{ $invoice->shipping_fee }}</td>
						</tr>
						@endif
						<tr>
							<th class="text-right">Total:</th>
							<td class="text-right">&#8369;{{ $invoice->total }}</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer')
<div class="row bg-dark">
	<div class="col-md-12">
		<div id="checkout-footer">
			<center class="">{{ config('app.name') }} &copy; {{ date('Y') }}</center>	
		</div>
		
	</div>
</div>
@endsection