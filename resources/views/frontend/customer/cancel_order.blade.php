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
			    <li class="breadcrumb-item active" aria-current="page">Cancel Order</li>
			  </ol>
			</nav>
		</div>
	</div>	
	<div class="row">
		<div class="col-3">
			@include('frontend.customer.list_bar')
		</div>
		<div class="col-md-9">
			<div class="clearfix">
				<h2 class="float-left">Cancel Order</h2>
				<a href="{{route('customer.order.details',['order'=>$order->number])}}" class="btn btn-primary float-right">Back to Order details</a>
			</div>
			<div class="card">
				<div class="card-body">
					<h4 class="mb-5">Order #{{ $order->number }}</h4>
					<form method="POST" action="{{route('submit_cancel_request')}}">
						{{ csrf_field()}}
					<p class="mb-4">Why are you requesting for cancellation?</p>
						<div>
							<input type="hidden" name="order_number" value="{{$order->number}}">
							<input type="hidden" name="customer_id" value="{{Auth::guard('customer')->user()->id}}">
							<div class="form-group row">
				                <label for="" class="col-sm-2 col-form-label">Reason:</label>
				                <div class="col-sm-6">
				                    <select class="form-control" required name="reason">
				                    	@if (count($reasons) > 0)
				                    		<option value="" disabled>Please select a reason</option>
				                    		@foreach ($reasons as $reason)
				                    		<option value="{{$reason->id}}">{{$reason->title}}</option>
				                    		@endforeach
				                    	@else
				                    		<option value="" disabled>No available reason.</option>
				                    	@endif
				                    </select>
				                </div>
				            </div>
						    <div class="form-group row">
				                <label for="comment" class="col-sm-2 col-form-label">Comment:</label>
				                <div class="col-sm-8">
				                   <textarea class="form-control" name="comment" required rows="6"></textarea>
				                </div>
				            </div>
				            <div class="form-group row">
			                    <label for="" class="col-sm-2 col-form-label"></label>
			                    <div class="col-sm-8">
			                        <button type="submit" class="btn btn-dark">Submit</button>
			                    </div>
			                </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
</div>
@endsection