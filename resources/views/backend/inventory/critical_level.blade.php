@extends('backend.backend_template')

@section('content')
<inventory-critical-level :admin="{{ Auth::guard('admin')->user() }}"></inventory-critical-level>
@endsection