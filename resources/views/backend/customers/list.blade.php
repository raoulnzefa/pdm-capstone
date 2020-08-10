@extends('backend.backend_template')

@section('content')
<customer-report :admin="{{ Auth::guard('admin')->user() }}"></customer-report>
@endsection