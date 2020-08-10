@extends('backend.backend_template')

@section('content')
<div class="container-fluid">
	<div class="row mb-2 justify-content-center">
		<div class="col-md-12">
			<div class="">
				<h2>Sales</h2>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
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
						&#8369;{{$monthly_sales}}</span>
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
	</div><!-- row -->
</div>
@endsection