@extends('backend.backend_template')

@section('content')
<div class="row">
	<div class="col-md-12">
		<nav class="mt-4">
		  	<div class="nav nav-tabs" id="nav-tab" role="tablist">
		    	<a class="nav-item nav-link active h3" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Sales</a>
		    	<a class="nav-item nav-link h3" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Best Selling</a>
		  	</div>
		</nav>
		<div class="tab-content" id="nav-tabContent">
		  	<div class="tab-pane fade show active pt-4" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
		  		<sales-report :admin="{{ Auth::guard('admin')->user() }}"></sales-report>
		  	</div>
		  	<div class="tab-pane fade pt-4" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
		  		<best-selling-report :admin="{{ Auth::guard('admin')->user() }}"></best-selling-report>
		  	</div>
		</div>
	</div>
</div>
@endsection