@extends('frontend.frontend_template')

@section('store_header')
<nav class="navbar navbar-expand-sm bg-dark navbar-dark" id="partial-header">
	<div class="container-fluid">
	 	<div class="navbar-brand">
      	<img src="{{ asset('images/logo.jpg') }}" width="125" height="60" alt="INFINITY FIGHTGEAR LOGO">
     	</div>
</nav>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-12">
			<center>
				<h3 style="font-weight: bolder; font-size: 40px; margin-top: 100px;" class="mb-2"><i class="fa fa-check-circle text-success"></i>&nbsp;Your cancellation request has been submitted.</h3>
				<a href="{{ route('customer.orders') }}" class="mt-4 btn btn-success">Go to My account</a>
			</center>
		</div>
	</div>		
</div>
@endsection