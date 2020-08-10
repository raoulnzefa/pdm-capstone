@extends('frontend.frontend_template')

@section('content')
<div class="container mb-4">
		<div class="row">
			<div class="col">
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
				    
				    <li class="breadcrumb-item active" aria-current="page">About Us</li>
				  </ol>
				</nav>
			</div>	
		</div><!-- row 1 -->
		<div class="row mb-4">
			<div class="col-md-12">
				<h4 class="ifg-header">Infinity Fightgear</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8">
				<div>
					<p>Infinity Fightgear is one stop shop that provides locally manufactured equipment and accessories for the fitness enthusiast to the professional fighter. The brand highlights each individual's personality and a whole teams' passion with its handcrafted creations.</p>
					<p>Innovative, Comfortable, Competitive yet affordable are the principles that goes into each product. Infinity Fightgear allows you to experience the exhilarating rush of training for a fight or reaching your personal goals.</p>
					<p>Infinity Fightgear offers a wide variety of products that caters to all ages and personalities.</p>
					<p>Infinity Fightgear offers a variety of collection including training equipment, gym accessories and fight apparel. Infinity Fightgear's collection provides a wide variety of merchandise for boxing, muay thai and MMA. Working hand in hand with clients to have complete freedom to customize their gear with their own concept and personality. Every product is designed to elevate your training experience.</p>
					<br>
					<h6>PRODUCT RANGE</h6>
					<p class="mb-0">TRAINING ACCESSORIES (70%)</p>
					<p class="mb-0">FIGHT APPAREL (25%)</p>
					<p>GYM ACCESSORIES (5%)</p>
				</div>
			</div>
			<div class="col-md-4">
				<img src="{{ asset('images/sm-north.jpg')}}" class="img-fluid">
			</div>
		</div>
	</div>
@endsection