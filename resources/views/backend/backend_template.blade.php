<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $data }} - {{ (!is_null($company)) ? $company->name : 'Infinity Fightgear' }}</title>
        <link rel="icon" type="image/png" href="{{ (!is_null($company)) ? $company->logo_url : asset('images/logo.jpg') }}">
        <link href="/css/my-admin.css" rel="stylesheet" type="text/css">
        <link href="/template_css/styles.css" rel="stylesheet" />
        <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css"> --}}
        <link href="/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        
        <link href="/css/report_style.css" rel="stylesheet" media="print" type="text/css">
       
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
       
    </head>
    <body class="sb-nav-fixed">
        <div id="app">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="{{ route('admin_dashboard') }}">{{ (!is_null($company)) ? $company->name : 'Infinity Fightgear' }}</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 mr-auto" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar-->
             <ul class="navbar-nav">
                 <li class="nav-item">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="usernameDropdown" role="button" data-toggle="dropdown"><i class="fa fa-user nav-icon"></i> {{Auth::guard('admin')->user()->username}}</a>
                        <div class="dropdown-menu" aria-labelledby="usernameDropdown">
                            <a class="dropdown-item" href="/admin/user/edit-information">Edit information</a>
                        </div>
                    </li>
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
                                Inventory <inventory-badge></inventory-badge>
                            </a>
                            <a class="nav-link" href="{{ route('replacements') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                                Replacements <replacement-badge></replacement-badge>
                            </a>
                            <a class="nav-link" href="{{ route('sales') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-poll-h"></i></div>
                                Sales
                            </a>
                            <a class="nav-link" href="{{ route('customers') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Customers
                            </a>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                                Maintenance
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse accordion-link" id="collapsePages" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionMaintenance">
                                    <a class="nav-link" href="{{ route('company_details') }}">Company Details</a>
                                    <a class="nav-link" href="{{ route('bank_account') }}">Bank Account</a>
                                    <a class="nav-link" href="{{ route('shipping_rate') }}">Shipping Rate</a>
                                    <a class="nav-link" href="{{ route('discount') }}">Discount</a>
                                    <a class="nav-link" href="{{ route('categories') }}">Categories</a>
                                    <a class="nav-link" href="{{ route('products') }}">Products</a>
                                    <a class="nav-link" href="{{ route('users') }}">Users</a>
                                    
                                </nav>
                            </div>
                            <a class="nav-link" href="{{ route('audit_trail') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                                Audit Trail
                            </a>
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
                            <div class="text-muted">Copyright &copy; {{ (!is_null($company)) ? $company->name : 'Infinity Fightgear'  }} {{ date('Y') }}</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div><!-- #layoutSidenav -->
        </div>
       
        <script src="/vendor/jquery/jquery.min.js"></script>
        <script src="/js/app.js"></script>
        <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="/template_js/scripts.js"></script>
       
    </body>
</html>
