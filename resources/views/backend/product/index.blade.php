@extends('backend.backend_template')

@section('content')
<product-list :admin="{{ Auth::guard('admin')->user() }}"></product-list>
@endsection