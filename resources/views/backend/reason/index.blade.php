@extends('backend.backend_template')

@section('content')
<reason-index :admin="{{ Auth::guard('admin')->user() }}"></reason-index>
@endsection