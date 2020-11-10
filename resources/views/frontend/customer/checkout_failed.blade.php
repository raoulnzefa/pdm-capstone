@extends('frontend.frontend_template')

@section('store_header')
<nav class="navbar navbar-expand-sm bg-dark navbar-dark" id="partial-header">
	<div class="container-fluid">
	 	<div class="navbar-brand">
      	<img src="{{ (!is_null($company)) ? $company->logo_url : asset('images/logo.jpg') }}" width="125" height="60" alt="{{$company->name}}">
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
						<h2 class="mb-4"><i class="fa fa-warning text-danger" style="font-size: 45px;"></i>&nbsp;Something went wrong. We'll take you back to checkout so you can try again.</h2>
						<a href="/checkout" class="btn btn-success btn-lg">Try again</a>
					</center>
				</div>
			</div>	
		</div><!-- row 1 -->
	</div>
</div>
@endsection