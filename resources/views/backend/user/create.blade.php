@extends('backend.backend_template')

@section('content')
<create-user :admin="{{ Auth::guard('admin')->user() }}"></create-user>
@endsection