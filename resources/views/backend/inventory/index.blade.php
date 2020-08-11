@extends('backend.backend_template')

@section('content')
<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>Product</th>
			<th>Stock</th>
			<th>Critical Level</th>
		</tr>
	</thead>
	<tbody>
		@foreach($inventories as $inventory)
			<tr>
				<td>{{ $inventory->number }}</td>
				<th>{{ $inventory->product_name.' '.$inventory->variant_value}}</th>
				<th>{{ $inventory->inventory_stock }}</th>
				<th>{{ $inventory->inventory_critical_level }}</th>
			</tr>
		@endforeach
	</tbody>
</table>
{{-- <inventory-list :admin="{{ Auth::guard('admin')->user() }}"></inventory-list> --}}
@endsection