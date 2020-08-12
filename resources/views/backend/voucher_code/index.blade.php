@extends('backend.backend_template')

@section('content')
<voucher-code-index :admin="{{ Auth::guard('admin')->user() }}"></voucher-code-index>
@endsection