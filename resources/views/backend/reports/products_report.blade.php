@extends('backend.backend_template')

@section('content')
<products-report :admin="{{ Auth::guard('admin')->user() }}"></products-report>
@endsection