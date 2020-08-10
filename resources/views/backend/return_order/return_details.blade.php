@extends('backend.backend_template')

@section('content')
<div class="mb-5">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<a href="{{route('return_requests')}}" class="d-block mb-2 text-secondary"><i class="fa fa-chevron-left"></i>&nbsp;Back</a>
			@if (session()->has('return-request-success'))
				<div class="alert alert-success">
					{{session()->get('return-request-success')}}
				</div>
			@endif
			@if (session()->has('return-request-error'))
				<div class="alert alert-danger">
					{{session()->get('return-request-error')}}
				</div>
			@endif
			<div class="card">
				<div class="card-body">
					<div class="mb-3 clearfix">
						<h2 class="float-left">Return Request Details</h2>
						<div class="float-right">
							@if ($returnRequest->status == "Pending")
							<div class="clearfix">
								<a href="{{route('decline_return_form',['returnRequest'=>$returnRequest])}}" class="btn btn-dark float-right">Decline</a>
								<form method="POST" action="{{route('approve_return_request', ['returnRequest'=>$returnRequest->id])}}" class="float-right mr-1">
									{{csrf_field()}}
									{{method_field('PUT')}}
									<input type="hidden" name="test" value="HEY">
									<button type="submit" class="btn btn-dark">Approve</button>
								</form>
							</div>
							@endif

							@if ($returnRequest->status == "Approved")
								<form method="POST" action="{{route('complete_return_request', ['returnRequest'=>$returnRequest->id])}}">
									{{csrf_field()}}
									{{method_field("PUT")}}
									<input type="hidden" name="test" value="HEY">
									<button class="btn btn-dark">Complete</button>
								</form>
							@endif
						</div>
					</div>
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
@endsection