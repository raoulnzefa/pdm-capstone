@extends('backend.backend_template')

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="clearfix mb-4 mt-4">
			<h3 class="float-left">Order Details</h3>
			<a href="{{ route('orders') }}" class="btn btn-secondary float-right">Back</a>
		</div>
		<order-details 
			order_num="{{ $order->number }}" 
			:admin="{{ Auth::guard('admin')->user() }}">
		</order-details>
	</div>
</div>

@endsection