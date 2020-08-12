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
				@if ($inventory->inventoryVariant)
					<td>{{$inventory->product->product_name.' '.$inventory->inventoryVariant->productWithVariant->variant_value}}</td>
				@else
					<td>{{$inventory->product->product_name }}</td>
				@endif
				<td>{{ $inventory->inventory_stock }}</td>
				<td>{{ $inventory->inventory_critical_level }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
{{-- <inventory-list :admin="{{ Auth::guard('admin')->user() }}"></inventory-list> --}}
@endsection