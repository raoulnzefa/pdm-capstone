@extends('backend.backend_template')

@section('content')
<inventory-out-of-stock :admin="{{ Auth::guard('admin')->user() }}"></inventory-out-of-stock>
@endsection