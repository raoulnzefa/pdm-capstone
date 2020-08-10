@extends('backend.backend_template')

@section('content')
<return-products-report :admin="{{ Auth::guard('admin')->user() }}"></return-products-report>
@endsection