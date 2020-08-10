@extends('backend.backend_template')

@section('content')
<monthly-sales :admin="{{ Auth::guard('admin')->user() }}"></monthly-sales>
@endsection