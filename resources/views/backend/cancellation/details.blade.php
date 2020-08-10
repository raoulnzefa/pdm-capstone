@extends('backend.backend_template')

@section('content')
<div class="row justify-content-center">
	<div class="col-md-8">
		<a href="{{route('cancellation_requests')}}" class="d-block mb-2 text-secondary"><i class="fa fa-chevron-left"></i>&nbsp;Back</a>
		@if (session()->has('return-request-success'))
			<div class="alert alert-success">
				{{session()->get('return-request-success')}}
			</div>
		@endif
		@if (session()->has('c-request-error'))
			<div class="alert alert-danger">
				{{session()->get('c-request-error')}}
			</div>
		@endif
		<div class="card mb-5">
			<div class="card-body">
				<div class="mb-3 clearfix">
					<h2 class="float-left">Cancellation Request Details</h2>
					<div class="float-right">
						@if ($cancellation->status == "Pending")
						<a href="{{route('cancellation_decline_form', ['cancellation'=>$cancellation->id])}}" class="btn btn-dark float-right">Decline</a>
						<form method="POST" class="float-right mr-1" action="{{route('approve_cancellation')}}">
							{{csrf_field()}}
							{{method_field('PUT')}}
							<input type="hidden" name="cancellation_id" value="{{$cancellation->id}}">
							<button type="submit" class="btn btn-dark">Approve</button>
						</form>
						@elseif ($cancellation->status == "Awaiting Refund")
						<h3>Awaiting refund</h3>
						@endif
					</div>
				</div><!-- div clearfix-->
				<div class="mb-5 pt-3">
					<div class="form-group row">
	                    <label for="reason" class="col-sm-3 col-form-label">Order Number:</label>
	                     <div class="col-sm-5">
	                        <a href="{{route('order_details_admin', ['order'=>$cancellation->order_number])}}" title="View Order"><h4 class="mb-0 mt-1">{{$cancellation->order_number}}</h4></a>
	                    </div>
	                </div>
					<div class="form-group row">
	                    <label for="reason" class="col-sm-3 col-form-label">Reason for Cancellation:</label>
	                     <div class="col-sm-5">
	                        <input type="text" class="form-control" id="reason" value="{{$cancellation->reason->title}}" readonly>
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <label for="reason" class="col-sm-3 col-form-label">Customer Comment:</label>
	                     <div class="col-sm-5">
	                        <textarea class="form-control" rows="4" readonly>{{$cancellation->comment}}</textarea>
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <label for="reason" class="col-sm-3 col-form-label">Return Status:</label>
	                     <div class="col-sm-5">
	                       <input type="text" name="" class="form-control" value="{{$cancellation->status}}" readonly>
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <label for="reason" class="col-sm-3 col-form-label">Request Created:</label>
	                     <div class="col-sm-5">
	                       <input type="text" name="" class="form-control" value="{{$cancellation->date_request}}" readonly>
	                    </div>
	                </div>
	            </div>
			</div>
		</div>
	</div>				
</div>
@endsection