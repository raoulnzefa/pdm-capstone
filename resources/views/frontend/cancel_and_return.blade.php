@extends('frontend.frontend_template')

@section('content')
<div class="container mb-5">
		<div class="row">
			<div class="col">
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
				    
				    <li class="breadcrumb-item active" aria-current="page">Cancel and Return</li>
				  </ol>
				</nav>
			</div>	
		</div><!-- row 1 -->
		<div class="row">
			<div class="col-md-12">
				<h4 class="ifg-header mb-5">Cancel and Return</h4>
				<div>
					<p class="mb-1"><b>Return, Refund and Replacement</b></p>
					<p>New merchandise (not washed, worn, altered or soiled), suitable for resale, may be returned for refund or replacement. For return product, you may bring it to the shop or ship it back within 7 days of purchase. Once Infinity Fightgear receives the returned product, a full refund otherwise a replacement of good stock of the same ordered product will be shipped to you after purchasing upon checking the product. All returns or for replacement product will be at the discretion of Infinity Fightgear shop.</p>
					<p class="mb-1"><b>Return Policy</b></p>
					<p>All merchandise returned for refund or replacement must be in perfect saleable condition.</p>
					<p>To return or replace an Infinity Fightgear product, please follow the instructions below:</p>
					<ul>
						<li>First, the customer must login from the Webfinity. Then, customer will go to his account page and click the menu for Completed Orders. Then click the Return button. The customer will be re-direct to the Return Request Form. The customer must fill up onto the Return Request Form to click the Submit button. The customer will wait for the update of his request. If the request is approved, an email notification will be send from the email address of the customer.</li>
						<li>For paid orders, customer can claim the refund using the same Payment method use in paying his ordered product or may visit the Infinity Fightgear shop to personally claim the refund in cash.</li>
						<li>The product should be returned in its original packaging and condition.</li>
					</ul>
					<p>ONLINE RETURN POLICY * * Note: Please make sure you order the correct size and color of the product. If you order the incorrect product size or color, you will be responsible for the shipping charges when you send them back.</p>
					<p>REPLACEMENT POLICY * * Note: For replacement, customer will receive the same product of his order. If the product is not available, Webfinity will offer the same category of his ordered product.</p>
					<p class="mb-1"><b>Cancellation Policy</b></p>
					<p>The customer may request a cancellation of his order through the website.</p>
					<p>For cancellation of orders, please follow the instructions below:</p>
					<ul>
						<li>First, the customer must login from the Webfinity. Then, customer will go to his account page and click the menu for Order Status Page to view the status of his order. Then click the Cancel button. The customer will be re-direct to the Cancellation Form. The customer must fill up onto the Cancellation Form to click the Submit button. An email notification will be send from the email address of the customer.</li>
					</ul>
					<p>ONLINE CANCELLATION POLICY * * Note: For unpaid orders, customer can cancel his entire order as long as the request of cancellation of his order is approved. For paid orders, customer can also cancel his entire order or even a part of his order as long as the product is not already shipped.</p>
					
				</div>
			</div>
			
		</div>
	</div>
@endsection