@extends('backend.backend_template')

@section('content')
<sales-component :admin="{{ Auth::guard('admin')->user() }}"></sales-component>
@endsection