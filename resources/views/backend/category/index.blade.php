@extends('backend.backend_template')

@section('content')
<category-index :admin="{{ Auth::guard('admin')->user() }}"></category-index>
@endsection