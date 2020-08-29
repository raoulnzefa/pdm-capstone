@extends('backend.backend_template')

@section('content')
<admin-replacements :admin="{{ Auth::guard('admin')->user() }}"></admin-replacements>
@endsection