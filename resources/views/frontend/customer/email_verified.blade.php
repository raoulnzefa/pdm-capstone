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
			<br>
			<div class="text-center mt-5">
				<h2 class="ifg-header mb-3"><i class="fa fa-check-circle text-success"></i>&nbsp;Your email has been successfully verified.</h3>
				<h5 class="mb-4">You can now login using your email and password.</h5>
				<a href="/login" class="btn btn-success btn-lg">Go to Login</a>
			</div>
		</div>
	</div>		
</div>
@endsection