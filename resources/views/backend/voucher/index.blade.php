@extends('backend.backend_template')

@section('content')
<voucher-index :admin="{{ Auth::guard('admin')->user() }}"></voucher-index>
@endsection