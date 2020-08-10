@extends('backend.backend_template')

@section('content')
	<user-list :admin="{{ Auth::guard('admin')->user() }}"></user-list>
@endsection