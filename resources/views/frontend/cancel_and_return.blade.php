@extends('frontend.frontend_template')

@section('content')
<div class="container mb-5">
		<div class="row">
			<div class="col">
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
				    
				    <li class="breadcrumb-item active" aria-current="page">Return Policy</li>
				  </ol>
				</nav>
			</div>	
		</div><!-- row 1 -->
		<div class="row">
			<div class="col-md-12">
				<h4 class="ifg-header mb-5">Return Policy</h4>
				<div>
					@if (!is_null($company))
					{!! htmlspecialchars_decode($company->return_policy) !!}
					@endif
				</div>
			</div>
			
		</div>
	</div>
@endsection