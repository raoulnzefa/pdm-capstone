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
			    <li class="breadcrumb-item"><a href="{{ route('customer.cancellation') }}">Cancellation</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Request Details</li>
			  </ol>
			</nav>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-3">
			@include('frontend.customer.list_bar')
		</div>
		<div class="col-md-9">
			@if ($cancelRequest->status != "Approved")
			<div class="clearfix">
				<h2 class="float-left">Cancellation Request Details</h2>
				<div class="float-right"><button class="btn btn-dark">Withdraw Cancellation</button></div>
			</div>
			@else
				<h2>Cancellation Request Details</h2>
			@endif
			<div class="mt-5">
				<div class="form-group row">
	                <label for="" class="col-sm-2 col-form-label">Order Number:</label>
	                <div class="col-sm-6">
	                    <input type="text" name="" value="{{$cancelRequest->order_number}}"  class="form form-control" readonly>
	                </div>
	            </div>
	            <div class="form-group row">
	                <label for="" class="col-sm-2 col-form-label">Reason:</label>
	                <div class="col-sm-6">
	                    <input type="text" name="" value="{{$cancelRequest->reason->title}}"  class="form form-control" readonly>
	                </div>
	            </div>
	            <div class="form-group row">
	                <label for="" class="col-sm-2 col-form-label">Comment:</label>
	                <div class="col-sm-6">
	                    <textarea rows="5" class="form-control" readonly>{{$cancelRequest->comment}}</textarea>
	                </div>
	            </div>
	            <div class="form-group row">
	                <label for="" class="col-sm-2 col-form-label">Status:</label>
	                <div class="col-sm-6">
	                    <input type="text" name="" value="{{$cancelRequest->status}}"  class="form form-control" readonly>
	                </div>
	            </div>
	            <div class="form-group row">
	                <label for="" class="col-sm-2 col-form-label">Date Requested:</label>
	                <div class="col-sm-6">
	                    <input type="text" name="" value="{{$cancelRequest->date_request}}"  class="form form-control" readonly>
	                </div>
	            </div>
	            <div class="form-group row">
	                <label for="" class="col-sm-2 col-form-label"></label>
	                <div class="col-sm-6">
	                   <a href="{{route('customer.cancellation')}}" class="btn btn-dark">Back</a>
	                </div>
	            </div>
			</div>
		</div>
	</div>

</div>
@endsection