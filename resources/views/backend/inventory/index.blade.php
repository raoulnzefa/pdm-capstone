@extends('backend.backend_template')

@section('content')
<inventory-list :admin="{{ Auth::guard('admin')->user() }}"></inventory-list>
@endsection