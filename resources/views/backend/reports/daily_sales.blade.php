@extends('backend.backend_template')

@section('content')
<daily-sales :admin="{{ Auth::guard('admin')->user() }}"></daily-sales>
@endsection