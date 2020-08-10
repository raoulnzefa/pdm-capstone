@extends('backend.backend_template')

@section('content')
<cancel-order-refund :order="{{ $order }}"></cancel-order-refund>
@endsection