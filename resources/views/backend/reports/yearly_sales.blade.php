@extends('backend.backend_template')

@section('content')
<yearly-sales :admin="{{ Auth::guard('admin')->user() }}"></yearly-sales>
@endsection