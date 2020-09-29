@extends('backend.backend_template')

@section('content')

<div class="row">
	<div class="col-md-12">
			<h2 class="mt-4 mb-4">Dashboard</h2>
	</div>
</div>
<div class="row mb-4">
	<div class="col-md-12">
		<nav class="navbar navbar-light bg-light">
			<span class="navbar-brand mb-0 h1">Sales</span>
		</nav>
	</div>
</div>
<div class="row">
	<div class="col-md-3">
		<div class="card dash-bg-card">
			<div class="card-body">
				<h2 class="text-center mb-3 dash-txt-name">Daily</h2>
				<h2 class="text-center bg-white">
					&#8369;{{$daily_sales}}
				</h2>	
			</div>
		</div><!-- card -->
	</div>
	<div class="col-md-3">
		<div class="card dash-bg-card">
			<div class="card-body">
				<h2 class="text-center mb-3 dash-txt-name">Weekly</h2>
				<h2 class="text-center bg-white">
					&#8369;{{$weekly_sales}}
				</h2>	
			</div>
		</div><!-- card -->
	</div>
	<div class="col-md-3">
		<div class="card dash-bg-card">
			<div class="card-body">
				<h2 class="text-center mb-3 dash-txt-name">Monthly</h2>
				<h2 class="text-center bg-white">
					&#8369;{{$monthly_sales}}
				</h2>	
			</div>
		</div><!-- card -->
	</div>
	<div class="col-md-3">
		<div class="card dash-bg-card">
			<div class="card-body">
				<h2 class="text-center mb-3 dash-txt-name">Yearly</h2>
				<h2 class="text-center bg-white">
					&#8369;{{$yearly_sales}}
				</h2>	
			</div>
		</div><!-- card -->
	</div>
</div>
@endsection