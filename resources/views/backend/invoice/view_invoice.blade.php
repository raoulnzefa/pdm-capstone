@extends('backend.backend_template')

@section('content')
<view-invoice invoice="{{$invoice}}" previous_url="{{$previous_url}}"></view-invoice>
@endsection