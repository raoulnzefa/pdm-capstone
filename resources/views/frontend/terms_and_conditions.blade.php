@extends('frontend.frontend_template')

@section('content')
<div class="container mb-5">
		<div class="row">
			<div class="col">
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
				    
				    <li class="breadcrumb-item active" aria-current="page">Terms and Conditions</li>
				  </ol>
				</nav>
			</div>	
		</div><!-- row 1 -->
		<div class="row">
			<div class="col-md-12">
				<h4 class="ifg-header">Terms and Conditions</h4>
				<div class="pt-4">
					@if (!is_null($company))
					{!! htmlspecialchars_decode($company->terms_and_conditions) !!}
					@endif
				</div>
			</div>
			
		</div>
	</div>
@endsection