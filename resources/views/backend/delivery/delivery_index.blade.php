@extends('backend.backend_template')

@section('content')
<order-delivery-index :admin="{{ Auth::guard('admin')->user() }}"></order-delivery-index>
@endsection