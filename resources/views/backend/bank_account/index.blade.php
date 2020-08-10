@extends('backend.backend_template')

@section('content')
<bank-account-index :admin="{{ Auth::guard('admin')->user() }}"></bank-account-index>
@endsection