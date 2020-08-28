<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $data }} - {{ config('app.name') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
        <link href="{{ asset('css/my-admin.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('template_css/styles.css')}}" rel="stylesheet" />
        <link href="{{ asset('css/report_style.css') }}" rel="stylesheet" media="print" type="text/css">
        <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css"> --}}
        <link href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
       
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
       
    </head>
    <body class="sb-nav-fixed">
        <div id="app">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="index.html">{{ config('app.name') }}</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 mr-auto" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar-->
             <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ Auth::guard('admin')->user()->username }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.logout') }}"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Side navigation</div>
                            <a class="nav-link" href="{{ route('admin_dashboard') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="{{ route('orders') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                                Orders <order-badge></order-badge>
                            </a>
                            <a class="nav-link" href="{{ route('inventory') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div>
                                Inventory
                            </a>
                            <a class="nav-link" href="{{ route('replacements') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                                Replacements
                            </a>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-sliders-h"></i></div>
                                Maintenance
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse accordion-link" id="collapsePages" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionMaintenance">
                                    <a class="nav-link" href="{{ route('categories') }}">Categories</a>
                                    <a class="nav-link" href="{{ route('products') }}">Products</a>
                                    <a class="nav-link" href="{{ route('bank_account') }}">Bank account</a>
                                    <a class="nav-link" href="{{ route('shipping_rate') }}">Shipping rate</a>
                                    <a class="nav-link" href="{{ route('users') }}">Users</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReports" aria-expanded="false" aria-controls="collapseReports">
                                <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                                Reports
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse accordion-link" id="collapseReports" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionReports">
                                    <a class="nav-link" href="">Sales</a>
                                    <a class="nav-link" href="">Inventory</a>
                                    <a class="nav-link" href="">Customers</a>
                                    <a class="nav-link" href="">Best selling</a>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        {{ Auth::guard('admin')->user()->role }}
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; {{ config('app.name') }} {{ date('Y') }}</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div><!-- #layoutSidenav -->
        </div>
       
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
         

        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('template_js/scripts.js') }}"></script>
       
    </body>
</html>
