@extends('backend.backend_template')

@section('content')
<edit-information :admin="{{ Auth::guard('admin')->user() }}"></edit-information>
@endsection