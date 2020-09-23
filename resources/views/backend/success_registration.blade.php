@extends('backend.login')

@section('content')
<div class="container">
		<div class="row">
			<div class="col-md-12 mb-5">
				<br>
				<div class="mt-5">
					<center>
						<h2 style="font-weight: bolder; font-size: 40px; color:white;" class="mb-4"><i class="fa fa-check-circle text-success"></i>&nbsp;Your account has been created</h2>
						<a href="{{route('admin_login')}}" class="btn btn-success btn-lg">Login here</a>
					</center>
				</div>
			</div>	
		</div><!-- row 1 -->
	</div>
</div>
@endsection
