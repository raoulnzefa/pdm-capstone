@extends('backend.backend_template')

@section('content')
<shipping-rate-index :admin="{{ Auth::guard('admin')->user() }}"></shipping-rate-index>
@endsection