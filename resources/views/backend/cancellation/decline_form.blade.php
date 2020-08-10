@extends('backend.backend_template')

@section('content')
<div class="mb-5">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<a href="{{route('cancellation_details', ['cancellation'=>$cancellation->id])}}" class="d-block mb-2 text-secondary"><i class="fa fa-chevron-left"></i>&nbsp;Back</a>
			@if (session()->has('decline-request-error'))
				<div class="alert alert-danger">
					{{session()->get('decline-request-error')}}
				</div>
			@endif
			<div class="card">
				<div class="card-body">
					<h2 class="mb-5">Create Decline Message</h2>
					<form method="POST" action="{{route('submit_cancellation_decline')}}">
						{{csrf_field()}}
						<input type="hidden" name="cancellation_id" value="{{$cancellation->id}}">
						<div class="form-group">
							<label>Customer Email:</label>
							<input type="text" name="customer_email" class="form-control" value="{{$cancellation->customer->email}}" readonly>
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