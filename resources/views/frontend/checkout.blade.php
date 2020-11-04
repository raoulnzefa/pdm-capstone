@extends('frontend.frontend_template')

@section('store_header')
<nav class="navbar navbar-light bg-dark" id="checkout-header">
    <div class="container-fluid">
        <div class="navbar-brand">
            <img src="{{ (!is_null($company)) ? $company->logo_url : '' }}" width="125" height="60" alt="INFINITY FIGHTGEAR LOGO">
        </div>
    </div>
</nav>
@endsection
@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <checkout-page 
        :customer="{{$customer}}"
        :cart="{{$cart}}"
        :discount="{{$discount}}"
        :company="{{$company}}"
        :shipping_rate="{{json_encode($shipping_rate)}}">
    </checkout-page>
</div>
@endsection

@section('footer')
<div class="row bg-dark">
	<div class="col-md-12">
		<div id="checkout-footer">
			<center class="">{{ (!is_null($company)) ? $company->name : '' }} &copy; {{ date('Y') }}</center>	
		</div>
		
	</div>
</div>
@endsection