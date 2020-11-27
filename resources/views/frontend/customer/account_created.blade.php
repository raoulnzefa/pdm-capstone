@extends('frontend.frontend_template')

@section('store_header')
<nav class="navbar navbar-expand-sm bg-dark navbar-dark" id="partial-header">
	<div class="container-fluid">
	 	<div class="navbar-brand">
      	<img src="{{ (!is_null($company)) ? $company->logo_url : asset('images/logo.jpg') }}" class="img-responsive" alt="{{$company->name}}">
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
						<h2 style="font-weight: bolder; font-size: 40px;" class="mb-4"><i class="fa fa-check-circle text-success"></i>&nbsp;Your account has been created</h2>
						<h4 class="mb-4">Thank you for creating your account. A verification email has been sent to {{ session()->get('email_created') }}.</h4>
						<a href="{{route('frontend_homepage')}}" class="btn btn-success btn-lg">Back to Home page</a>
					</center>
				</div>
			</div>	
		</div><!-- row 1 -->
	</div>
</div>
@endsection