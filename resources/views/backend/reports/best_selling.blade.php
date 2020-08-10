@extends('backend.backend_template')

@section('content')
<best-selling-report :admin="{{ Auth::guard('admin')->user() }}"></best-selling-report>
@endsection