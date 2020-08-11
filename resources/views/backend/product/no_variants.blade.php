@extends('backend.backend_template')

@section('content')
<product-no-variants :admin="{{ Auth::guard('admin')->user() }}"></product-no-variants>
@endsection