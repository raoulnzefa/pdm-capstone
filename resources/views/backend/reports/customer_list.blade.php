@extends('backend.backend_template')

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card mt-4">
			<div class="card-header">
				<h2 class="mb-0">Customer List Report</h2>
			</div>
			<div class="card-body">
				<div class="row justify-content-center">
					<div class="col-md-4">
						<form>
							<center>
								<button type="submit" class="btn btn-primary">Generate</button>
							</center>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection