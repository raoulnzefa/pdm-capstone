@extends('backend.backend_template')

@section('content')
<weekly-sales :admin="{{ Auth::guard('admin')->user() }}"></weekly-sales>
@endsection