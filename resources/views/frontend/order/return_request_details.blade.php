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
			    <li class="breadcrumb-item"><a href="/my-account/returns">Returns</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Request Details</li>
			  </ol>
			</nav>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			@include('frontend.customer.list_bar')
		</div>
		<div class="col-md-9 mb-5">
			<div class="row">
				<div class="col-md-12">
					<div class="clearfix">
						<h2 class="float-left">Return Request Details</h2>
						<a href="/my-account/returns" class="float-right btn btn-dark">Back</a>
					</div>
					<div class="card">
						<div class="card-body">
							<div class="mb-5 pt-3">
								<div class="form-group row">
				                    <label for="reason" class="col-sm-3 col-form-label">Order Number:</label>
				                     <div class="col-sm-5">
				                        <input type="text" class="form-control" value="{{$returnRequest->order_number}}" readonly>
				                    </div>
				                </div>
								<div class="form-group row">
				                    <label for="reason" class="col-sm-3 col-form-label">Reason for Return:</label>
				                     <div class="col-sm-5">
				                        <input type="text" class="form-control" id="reason" value="{{$returnRequest->reason->title}}" readonly>
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="reason" class="col-sm-3 col-form-label">Customer Comment:</label>
				                     <div class="col-sm-5">
				                        <textarea class="form-control" rows="4" readonly>{{$returnRequest->comment}}</textarea>
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="reason" class="col-sm-3 col-form-label">Return Status:</label>
				                     <div class="col-sm-5">
				                       <input type="text" name="" class="form-control" value="{{$returnRequest->status}}" readonly>
				                    </div>
				                </div>
				               <div class="form-group row">
				                    <label for="reason" class="col-sm-3 col-form-label">Return Action:</label>
				                     <div class="col-sm-5">
				                       <input type="text" name="" class="form-control" value="{{$returnRequest->action}}" readonly>
				                    </div>
				                </div>
				                <div class="form-group row">
				                    <label for="reason" class="col-sm-3 col-form-label">Request Created:</label>
				                     <div class="col-sm-5">
				                       <input type="text" name="" class="form-control" value="{{$returnRequest->date_return_request}}" readonly>
				                    </div>
				                </div>
				                <table class="table table-condensed table-striped table-bordered mt-5 mb-0">
				                	<thead>
				                		<tr>
				                			<th>Product(s)</th>
				                			<th>Price</th>
				                			<th>Ordered</th>
				                			<th>Return</th>
				                			<th>Amount</th>
				                		</tr>
				                	</thead>
				                	<tbody>
				                		@foreach ($returnRequest->returnProductRequests as $prod)
				                		<tr>
				                			<td>
				                				<img src="/storage/products/{{$prod->orderProduct->inventory->product->image}}" class="img-fluid" width="20%" height="13%">
												<span class="align-middle">{{ $prod->orderProduct->product_name }}</span>
				                			</td>
				                			<td class="align-middle">&#8369;{{number_format($prod->price,2)}}</td>
				                			<td class="align-middle">{{$prod->ordered_qty}}</td>
				                			<td class="align-middle">{{$prod->quantity}}</td>
				                			<td class="align-middle">&#8369;{{$prod->amount}}</td>
				                		</tr>
				                		@endforeach
				                	</tbody>
				                </table>
				                <table class="table mb-4">
									<tr>
										<th class="text-right">Subtotal:</th>
										<td width="30%" class="text-right">&#8369;{{ number_format($returnRequest->subtotal,2) }}</td>
									</tr>
									<tr>
										<th class="text-right">Discount:</th>
										<td class="text-right">&#8369;{{ number_format($returnRequest->discount,2) }}</td>
									</tr>
									<tr>
										<th class="text-right">Total Amount:</th>
										<td class="text-right">&#8369;{{ number_format($returnRequest->total,2) }}</td>
									</tr>
								</table>
							</div>
						</div>
					</div><!-- card -->
				</div>
			</div>
		</div>
		{{-- <order-status :customer="{{ Auth::guard('customer')->user() }}"></order-status> --}}
	</div>

</div>
@endsection