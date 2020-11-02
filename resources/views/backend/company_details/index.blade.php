@extends('backend.backend_template')

@section('content')
<div class="row">
	<div class="col-md-12">
		<company-details :admin="{{ Auth::guard('admin')->user() }}"></company-details>
	</div>
</div>
@endsection