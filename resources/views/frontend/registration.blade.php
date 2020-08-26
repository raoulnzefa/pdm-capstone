@extends('frontend.frontend_template')

@section('content')
<div class="container mb-4">
	<div class="row">
	    <div class="col">
	        <nav aria-label="breadcrumb">
	          <ol class="breadcrumb mb-0 pl-0">
	            <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
	            <li class="breadcrumb-item active" aria-current="page">Create account</li>
	          </ol>
	        </nav>
	    </div>  
	</div><!-- row 1 -->
	<div class="row mt-2">
	    <div class="col-md-12">
	        @if ($errors->any())
	            <div class="alert alert-danger">
	                <strong>Whoops!</strong> There were some problems with your input.<br><br>
	                <ul>
	                    @foreach ($errors->all() as $error)
	                        <li>{{ $error }}</li>
	                    @endforeach
	                </ul>
	            </div>
	        @endif
	       	 @if ($message = Session::get('registration_error'))
	            <div class="alert alert-danger">
	                {{ $message }}
	            </div>
	        @endif
	    </div>
	</div><!-- row 2-->
	@if($old = Session::getOldInput())
	<registration-form :old="{{ json_encode($old) }}"></registration-form>
	@else
	<registration-form></registration-form>
	@endif
	</div><!-- row 5 -->
	
	</div>
@endsection