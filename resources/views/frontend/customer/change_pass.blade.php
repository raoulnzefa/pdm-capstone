@extends('frontend.frontend_template')

@section('store_header')
<nav id="mainNavbar" class="navbar navbar-expand-sm bg-dark">
  <!-- Brand -->
  <a class="navbar-brand" href="{{route('frontend_homepage')}}">
      <img src="{{ asset('images/logo.jpg') }}" width="125" height="60" alt="">
  </a>
  <div class="container-fluid">
  <!-- Links -->
      <ul class="navbar-nav">
          <li class="nav-item mr-2">
              <a class="nav-link text-light cool-link" href="{{ route('frontend_homepage') }}">HOME</a>
          </li>
      </ul>
      <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link text-light cool-link" href="/cart"><i class="fa fa-shopping-cart"></i>&nbsp;CART&nbsp;<cart-quantity :customer="{{ Auth::guard('customer')->user() }}"></cart-quantity></a>           
          </li>
 	       <li class="nav-item">
               <a class="nav-link text-light cool-link" href="{{ route('customer.logout') }}"><i class="fa fa-sign-in"></i>&nbsp;LOGOUT</a>
           </li>    
      </ul>
    
	</div>
</nav>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
			    <li class="breadcrumb-item active" aria-current="page">Change Password</li>
			  </ol>
			</nav>
		</div>
	</div>
	<div class="row">
		<div class="col-3">
			@include('frontend.customer.list_bar')
		</div>
		<div class="col-md-9">
			<change-pass :customer="{{ Auth::guard('customer')->user() }}"></change-pass>
		</div>
	</div>

</div>
@endsection