@extends('backend.backend_template')

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card mt-4">
			<div class="card-header">
				<h2 class="mb-0">Inventory Report</h2>
			</div>
			<div class="card-body pt-4">
				<div class="row justify-content-center">
					<div class="col-md-4">
						<form>
							<div class="form-group">
								<label>Type:</label>
								<select class="form-control">
									<option value="all_stocks">All Stocks</option>
							    	<option value="in_critical_level">In Critical Level</option>
							   	<option value="out_of_stocks">Out Of Stocks</option>
								</select>
							</div>
							<button type="submit" class="btn btn-primary">Generate</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection