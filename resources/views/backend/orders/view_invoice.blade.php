@extends('backend.backend_template')

@section('content')
<view-invoice id="{{ $order }}"></view-invoice>
@endsection