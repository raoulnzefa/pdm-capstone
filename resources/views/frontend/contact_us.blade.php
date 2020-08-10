@extends('frontend.frontend_template')

@section('content')
<div class="container">
		<div class="row">
			<div class="col">
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
				    
				    <li class="breadcrumb-item active" aria-current="page">Contact US</li>
				  </ol>
				</nav>
			</div>	
		</div><!-- row 1 -->
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<h4 class="ifg-header">Contact Us</h4>
						
						<div class="row mt-4">
							<div class="col-md-12">
								<form>
									<div class="form-group row">
										<label for="senderName" class="ifg-label col-sm-3 col-form-label">Name:</label>
										<div class="col-sm-9">
											<input type="text" name="" id="senderName" placeholder="" class="form-control" required>
										</div>
									</div>
									<div class="form-group row">
										<label for="senderEmail" class="ifg-label col-sm-3 col-form-label">Email:</label>
										<div class="col-sm-9">
											<input type="text" name="" id="senderEmail" placeholder="" class="form-control" requried>
										</div>
									</div>
									<div class="form-group row">
										<label for="senderMessage" class="ifg-label col-sm-3 col-form-label">Message:</label>
										<div class="col-sm-9">
											<textarea class="form-control" rows="5" name="" id="senderMessage" required></textarea>
										</div>
									</div>
									<div class="form-group row">
										<label for="" class="col-sm-3 col-form-label"></label>
										<div class="col-sm-9">
											<button type="submit" class="btn btn-primary ifg-btn"><i class="fa fa-check-circle"></i>&nbsp;Submit</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection