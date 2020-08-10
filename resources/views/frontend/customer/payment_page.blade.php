@extends('frontend.frontend_template')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
			    <li class="breadcrumb-item"><a href="/invoice/{{ $invoice->id }}">Invoice</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Payment</li>
			  </ol>
			</nav>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-12">
			<h4 class="mb-4">Payment</h4>

			<div class="card">
				<h5 class="card-header">Payment Method</h5>
				<div class="card-body">
					
					<payment-method :invoice="{{ $invoice }}" :order="{{ $invoice->order }}" :customer="{{ Auth::guard('customer')->user() }}"></payment-method>
					
				</div>
			</div>
		</div>
	</div>	
</div>
@endsection