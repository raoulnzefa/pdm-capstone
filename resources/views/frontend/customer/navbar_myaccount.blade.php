<nav id="mainNavbar" class="navbar navbar-expand-sm bg-dark">
  <!-- Brand -->
  <a class="navbar-brand" href="{{route('frontend_homepage')}}">
      <img src="{{ (!is_null($company)) ? $company->logo_url : '' }}" width="125" height="60" alt="">
  </a>
  <div class="container-fluid">
  <!-- Links -->
      <ul class="navbar-nav">
          <li class="nav-item mr-2">
              <a class="nav-link text-light cool-link" href="{{ url('/products') }}">PRODUCTS</a>
          </li>
      </ul>
      <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link text-light cool-link" href="javascript:void(0);"><i class="fa fa-user"></i> {{ strtoupper(Auth::guard('customer')->user()->first_name).' '.strtoupper(Auth::guard('customer')->user()->last_name) }}</a>           
          </li>
          <li class="nav-item">
            <a class="nav-link text-light cool-link" href="/cart"><i class="fa fa-shopping-cart"></i>&nbsp;CART&nbsp;<cart-quantity :customer="{{ Auth::guard('customer')->user() }}"></cart-quantity></a>           
          </li>
 	       <li class="nav-item">
               <a class="nav-link text-light cool-link" href="{{ route('customer.logout') }}"><i class="fa fa-sign-in"></i>&nbsp;LOGOUT</a>
           </li>    
      </ul>
    
	</div>
</nav>