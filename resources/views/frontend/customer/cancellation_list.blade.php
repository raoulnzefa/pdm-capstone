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
			    <li class="breadcrumb-item active" aria-current="page">Cancellation Requests</li>
			  </ol>
			</nav>
		</div>
	</div>	
	<div class="row">
		<div class="col-3">
			@include('frontend.customer.list_bar')
		</div>
		<div class="col-md-9">
			<div class="mb-5">
				<h2 class="mb-4">Cancellation Requests</h2>
				<table class="table table-bordered table-hover table-condensed table-striped">
					<thead>
						<tr>
							<th>Order #</th>
							<th>Date</th>
							<th>Status</th>
							<th>Details</th>
						</tr>
					</thead>
					<tbody>
						@if (count($cancellation_list) > 0)
							@foreach ($cancellation_list as $cancel_request)
							<tr>
								<td>{{$cancel_request->order_number}}</td>
								<td>{{$cancel_request->date_request}}</td>
								<td>{{$cancel_request->status}}</td>
								<td><a href="/cancellation-request/{{$cancel_request->id}}/details" class="btn btn-sm btn-primary">View</a></td>
							</tr>
							@endforeach
						@else
							<tr>
								<td colspan="4" align="center">No cancellation request.</td>
							</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>
@endsection