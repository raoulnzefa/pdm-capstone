@extends('backend.backend_template')

@section('content')
<inventory-variants :product="{{ $product }}"></inventory-variants>
@endsection