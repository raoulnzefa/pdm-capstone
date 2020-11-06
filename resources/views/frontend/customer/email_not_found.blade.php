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
			<div class="col-md-12 mb-5">
				<br>
				<div class="mt-5">
					<center>
						<div class="alert alert-danger pt-4 pb-4">
							<h2 style="font-weight: bolder; font-size: 40px;" class="text-danger">Sorry, we couldn't find that page.</h2>
						</div>
						<a href="{{route('frontend_homepage')}}" class="btn btn-success btn-lg">Go to Home page</a>
					</center>
				</div>
			</div>	
		</div><!-- row 1 -->
	</div>
</div>
@endsection