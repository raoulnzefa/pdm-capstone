@extends('backend.backend_template')

@section('content')
<customer-admin-details 
	:admin="{{ Auth::guard('admin')->user() }}"
	:customer_id="{{ $customerId }}"></customer-admin-details>
@endsection