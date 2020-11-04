@extends('frontend.frontend_template')

@section('content')
<div class="container mb-4">
		<div class="row">
			<div class="col">
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
				    
				    <li class="breadcrumb-item active" aria-current="page">About Us</li>
				  </ol>
				</nav>
			</div>	
		</div><!-- row 1 -->
		<div class="row mb-4">
			<div class="col-md-12">
				<h4 class="ifg-header">About Us</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8">
				<div>
					@if (!is_null($company))
						{!! $company->about_us !!}
					@endif
				</div>
			</div>
			<div class="col-md-4">
				<img src="{{ (!is_null($company)) ? $company->image_url : ''}}" alt="Infinity Fight Image" class="img-fluid">
			</div>
		</div>
	</div>
@endsection