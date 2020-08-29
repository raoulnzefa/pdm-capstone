@extends('backend.backend_template')

@section('content')
<admin-replacement-details :admin="{{ Auth::guard('admin')->user() }}" id="{{$requestId}}"></admin-replacement-details>
@endsection