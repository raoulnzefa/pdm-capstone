@extends('frontend.frontend_template')

@section('store_header')
<nav class="navbar navbar-expand-sm bg-dark navbar-dark" id="partial-header">
	<div class="container-fluid">
	 	<div class="navbar-brand">
      	<img src="{{ (!is_null($company)) ? $company->logo_url : asset('images/logo.jpg') }}" class="img-responsive" alt="{{$company->name}}">
     	</div>
</nav>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-12">
			<center>
				<h3 style="font-weight: bolder; font-size: 40px; margin-top: 100px;" class="mb-2"><i class="fa fa-check-circle text-success"></i>&nbsp;Bank deposit slip has been uploaded.</h3>
				<a href="{{ route('customer.view_order',['order'=> $order_number]) }}" class="mt-4 btn btn-success">Back to Order details</a>
			</center>
		</div>
	</div>		
</div>
@endsection