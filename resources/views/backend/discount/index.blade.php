@extends('backend.backend_template')

@section('content')
<discount-maintenance :admin="{{ Auth::guard('admin')->user() }}"></discount-maintenance>
@endsection