@extends('backend.backend_template')

@section('content')
<div class="row">
	<div class="col-md-12 pt-4">
		<order-details 
			order_num="{{ $order->number }}" 
			:admin="{{ Auth::guard('admin')->user() }}">
		</order-details>
	</div>
</div>

@endsection