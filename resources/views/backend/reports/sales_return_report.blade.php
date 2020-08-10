@extends('backend.backend_template')

@section('content')
<sales-return-report :admin="{{ Auth::guard('admin')->user() }}"></sales-return-report>
@endsection