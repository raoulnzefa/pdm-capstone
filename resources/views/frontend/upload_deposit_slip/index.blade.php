@extends('frontend.frontend_template')

@section('store_header')
	@include('frontend.customer.navbar_myaccount')
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="/order/{{$order->number}}/details">Order Details</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Upload Deposit Slip</li>
			  </ol>
			</nav>
		</div>
	</div>
	<div class="row">
		<div class="col-3">
			@include('frontend.customer.list_bar')
		</div>
		<div class="col-md-9">
			<div class="card">
				<div class="card-body">
					<h3>Upload Deposit Slip</h3>
					@if ($errors->any())
			            <div class="alert alert-danger">
			                <strong>Whoops!</strong> There were some problems with your input.<br><br>
			                <ul>
			                    @foreach ($errors->all() as $error)
			                        <li>{{ $error }}</li>
			                    @endforeach
			                </ul>
			            </div>
			        @endif
					<div class="mt-5">
						<form method="POST" action="{{route('upload_deposit_slip')}}" enctype="multipart/form-data">
							{{csrf_field()}}
							<input type="hidden" name="order_number" value="{{$order->number}}">
							<div class="form-group row">
			                    <label for="" class="col-sm-3 col-form-label text-right">Deposit slip:</label>
			                    <div class="col-sm-6">
			                       <upload-deposit-slip></upload-deposit-slip>
			                    </div>
			                </div>
			                <div class="form-group row">
			                    <label for="" class="col-sm-3 col-form-label text-right"></label>
			                    <div class="col-sm-6">
			                       <button type="submit" class="btn btn-dark">Upload</button>
			                    </div>
			                </div>
						</form>
					</div>
				</div><!-- card-body -->
			</div><!-- card -->
		</div>
	</div>

</div>
@endsection