@extends('backend.backend_template')

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card mt-4">
			<div class="card-header">
				<h2 class="mb-0">Audit Trail</h2>
			</div>
			<div class="card-body pt-4">
				<div class="row justify-content-center">
					<div class="col-md-4">
						<audit-trail :admin="{{ Auth::guard('admin')->user() }}"></audit-trail>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection