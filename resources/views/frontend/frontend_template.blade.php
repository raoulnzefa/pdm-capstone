<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $data }} - {{ (!is_null($company)) ? $company->name : '' }}</title>
        {{-- <title>{{ config('app.name') }}</title> --}}
        <link rel="icon" type="image/png" href="{{(!is_null($company)) ? $company->logo_url : ''}}">
       {{--  <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
        <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        
        <link href="/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="/css/mycss.css">
        <!-- Bootstrap core CSS-->
        

    </head>
    <body>
        <div id="app"> {{-- ID app --}}
            @section('store_header')
            <div class=""> {{-- navbar --}}
                <nav id="mainNavbar" class="navbar navbar-expand-lg navbar-dark bg-dark">
                    <!-- Brand -->
                    <a class="navbar-brand" href="{{route('frontend_homepage')}}">
                        <img src="{{(!is_null($company)) ? $company->logo_url : ''}}" class="img-responsive" alt="Infinity Fightgear Logo">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Links -->
                        <ul class="navbar-nav">
                            <li class="nav-item mr-2">
                                <a class="nav-link text-light cool-link" href="{{ route('frontend_homepage') }}">HOME</a>
                            </li>
                            <li class="nav-item mr-2">
                                <a class="nav-link text-light cool-link" href="{{ url('/products') }}">PRODUCTS</a>
                            </li>
                            <li class="nav-item mr-2">
                                <a class="nav-link text-light cool-link" href="{{ url('/about-us') }}">ABOUT US</a>
                            </li>
                            
                        </ul>
                        <ul class="navbar-nav ml-auto">
                            <form method="post" action="{{ route('search.product') }}">
                                @csrf
                                <div class="input-group">
                                  <input type="text" name="searchProduct" class="form-control" placeholder="Search product" required aria-label="" aria-describedby="basic-addon2" id="nav-search">
                                  <div class="input-group-append mr-2">
                                    <button class="btn btn-dark" type="submit"><i class="fa fa-search"></i></button>
                                  </div>
                                </div>
                            </form>
                            <li class="nav-item">
                                @auth('customer')
                                    <a class="nav-link text-light cool-link" href="/cart"><i class="fa fa-shopping-cart"></i>&nbsp;CART&nbsp;<cart-quantity :customer="{{ Auth::guard('customer')->user() }}"></cart-quantity></a>
                                @else
                                    <a class="nav-link text-light cool-link" href="/cart"><i class="fa fa-shopping-cart"></i>&nbsp;CART&nbsp;<span class="badge badge-pill badge-danger">0</span></a>
                                @endauth
                               
                            </li>
                            @auth('customer')
                                <li class="nav-item">
                                   {{--  <customer-name :customer="{{ Auth::guard('customer')->user() }}"></customer-name> --}}
                                   <a class="nav-link text-light cool-link" href="{{ route('customer.orders')}}"><i class="fa fa-user"></i>&nbsp;MY ACCOUNT</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-light cool-link" href="{{ route('customer.logout') }}"><i class="fa fa-sign-in"></i>&nbsp;LOGOUT</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link text-light cool-link" href="{{ url('login') }}"><i class="fa fa-sign-in"></i>&nbsp;LOGIN</a>
                                </li>
                            @endauth
                           
                        </ul>
                  </div>
                </nav>
            </div> {{-- navbar --}}
            @show
            <div id="mainContainer" style="background-color: #fff">
                <div class="container">
                @yield('content')
                </div>
            </div>
            @section('footer')
            <div id="footer-wrapper">
                <div class="row">
                    <div class="col-12">
                        <div class="container">
                            <div class="row">
                                
                                <div class="col-sm-4 footer-col">
                                    <h5>INFORMATION</h5>
                                    <ul class="">
                                        <li><a href="/products">Products</a></li>
                                        <li><a href="/about-us">About Us</a></li>
                                        <li><a href="/terms-and-conditions">Terms and Conditions</a></li>
                                        <li><a href="/return-policy">Return Policy</a></li>
                                        <!--  -->
                                    </ul>
                                </div>
                                <div class="col-sm-4 footer-col">
                                    <h5>CONTACT US</h5>
                                    <ul>
                                       <li>{{ (!is_null($company)) ? $company->name : ''}}</li>
                                       <li><address>{{(!is_null($company)) ? $company->address : ''}}</address></li>
                                       <li>Call us at {{ (!is_null($company)) ? $company->contact_number : ''}}</li>
                                    </ul>
                                </div>
                               
                            </div><!-- row -->
                            <hr>
                            <p style="color: #fff;">{{ (!is_null($company)) ? $company->name : '' }} &copy; {{ date('Y') }}</p>
                        </div><!-- container -->
                    </div><!-- col 12 -->
                </div><!-- row -->
            </div> {{-- footer-wrapper --}}
            @show
        </div> {{-- ID app --}}

        <script src="/vendor/jquery/jquery.min.js"></script>
        <script src="/js/app.js"></script>
        <script src="/vendor/popper/dist/umd/popper.min.js"></script>
        <script src="/vendor/bootstrap/js/bootstrap.min.js"></script>

        @yield('postJquery')
        
    </body>
</html>
