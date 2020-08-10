@extends('backend.backend_template')

@section('content')
<ship-order-form id="{{ $order->id }}"></ship-order-form>
@endsection