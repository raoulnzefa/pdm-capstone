@extends('backend.backend_template')

@section('content')
<customer-list :admin="{{ Auth::guard('admin')->user() }}"></customer-list>
@endsection