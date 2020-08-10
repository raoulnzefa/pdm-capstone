@extends('backend.backend_template')

@section('content')
<admin-change-pass :admin="{{ $admin }}"></admin-change-pass>
@endsection