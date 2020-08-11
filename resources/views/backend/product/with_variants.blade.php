@extends('backend.backend_template')

@section('content')
<product-with-variants :admin="{{ Auth::guard('admin')->user() }}"></product-with-variants>
@endsection