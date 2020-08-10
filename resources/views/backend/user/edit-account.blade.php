@extends('backend.backend_template')

@section('content')
<edit-account :admin="{{ $admin }}"></edit-account>
@endsection