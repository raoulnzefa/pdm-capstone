@extends('backend.backend_template')

@section('content')
<inventory-report :admin="{{ Auth::guard('admin')->user() }}"></inventory-report>
@endsection