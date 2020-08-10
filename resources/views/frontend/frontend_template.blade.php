<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>
        {{-- <title>{{ config('app.name') }}</title> --}}
        <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
        
        {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
        <!-- Bootstrap core CSS-->
        <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/mycss.css') }}">

    </head>
    <body>
        <div id="app"> {{-- ID app --}}
            @section('store_header')
            <div class=""> {{-- navbar --}}
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
                            <li class="nav-item mr-2">
                                <a class="nav-link text-light cool-link" href="{{ url('/products') }}">PRODUCTS</a>
                            </li>
                            <li class="nav-item mr-2">
                                <a class="nav-link text-light cool-link" href="{{ url('/about-us') }}">ABOUT US</a>
                            </li>
                            
                        </ul>
                        <ul class="navbar-nav ml-auto">
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
                                   <a class="nav-link text-light cool-link" href="/my-account"><i class="fa fa-user"></i>&nbsp;MY ACCOUNT</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-light cool-link" href="{{ route('customer.logout') }}"><i class="fa fa-sign-in"></i>&nbsp;LOGOUT</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link text-light cool-link" href="{{ url('register') }}"><i class="fa fa-user"></i>&nbsp;CREATE ACCOUNT</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-light cool-link" href="{{ url('login') }}"><i class="fa fa-sign-in"></i>&nbsp;LOGIN</a>
                                </li>
                            @endauth
                           
                        </ul>
                       <form method="post" action="{{ route('search.product') }}">
                            @csrf
                            <div class="input-group">
                              <input type="text" name="searchProduct" class="form-control" placeholder="Search product" required aria-label="" aria-describedby="basic-addon2">
                              <div class="input-group-append">
                                <button class="btn btn-dark" type="submit"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </form>
                  </div>
                </nav>
            </div> {{-- navbar --}}
            @show
            <div id="mainContainer" style="background-color: #fff">
                @yield('content')
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
                                        <li><a href="/cancel-and-return">Cancel and Return</a></li>
                                        <!--  -->
                                    </ul>
                                </div>
                                <div class="col-sm-4 footer-col">
                                    <h5>CONTACT US</h5>
                                    <ul>
                                       <li>INFINITY FIGHTGEAR</li>
                                       <li>Bunlo, Mac Arthur Hi-way, 2500 Bocaue, Bulacan</li>
                                       <li>Call us at 09987901118</li>
                                    </ul>
                                </div>
                               
                            </div><!-- row -->
                            <hr>
                            <p style="color: #fff;">{{ config('app.name') }} &copy; {{ date('Y') }}</p>
                        </div><!-- container -->
                    </div><!-- col 12 -->
                </div><!-- row -->
            </div> {{-- footer-wrapper --}}
            @show
        </div> {{-- ID app --}}



        {{-- <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script> --}}
        
        <script src="{{ asset('vendor/jquery/jquery.slim.min.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('vendor/popper/dist/umd/popper.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
        
        {{-- <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
        @isset($data)
        <script type="text/javascript">
            document.title = "{{ config('app.name') }} - {{ $data }}";
            
            jQuery(document).ready(function() {
                @yield('postJquery');
            });
        
        </script>
        @endisset
        
    </body>
</html>
