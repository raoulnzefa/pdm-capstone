@extends('backend.backend_template')

@section('content')
<refund-orders-report :admin="{{ Auth::guard('admin')->user() }}"></refund-orders-report>
@endsection