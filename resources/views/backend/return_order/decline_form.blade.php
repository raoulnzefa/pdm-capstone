@extends('backend.backend_template')

@section('content')
<div class="mb-5">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<a href="{{route('return_request_details', ['returnRequest'=>$returnRequest])}}" class="d-block mb-2 text-secondary"><i class="fa fa-chevron-left"></i>&nbsp;Back</a>
			@if (session()->has('decline-request-error'))
				<div class="alert alert-danger">
					{{session()->get('decline-request-error')}}
				</div>
			@endif
			<div class="card">
				<div class="card-body">
					<h2 class="mb-5">Create Decline Message</h2>
					<form method="POST" action="{{route('decline_return_request', ['returnRequest'=>$returnRequest])}}">
						{{csrf_field()}}
						{{method_field('PUT')}}
						<div class="form-group">
							<label>Customer Email:</label>
							<input type="text" name="customer_email" class="form-control" value="{{$returnRequest->customer->email}}" readonly>
						</div>
						<div class="form-group">
							<label>Message:</label>
							<textarea name="message" class="form-control" rows="6" required></textarea>
						</div>
						<div>
							<button type="submit" class="btn btn-dark">Send</button>
						</div>
					</form>
				</div><!-- card-body -->
			</div><!-- card -->
		</div>
	</div>
</div>
@endsection