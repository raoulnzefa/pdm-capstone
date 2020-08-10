<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>INFINITY FIGHTGEAR Administration</title>

        <!-- Fonts -->
        <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
      {{--   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --}}
        <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        {{-- <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css"> --}}
        <link href="{{ asset('css/report_style.css') }}" rel="stylesheet" media="print" type="text/css">
       
        {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css"> --}}
        <link href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/my-admin.css') }}" rel="stylesheet" type="text/css">

    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-lg navbar-dark bg-nav-admin" id="admin-navbar">
                <a class="navbar-brand" href="/admin/home">
                    <img src="{{ asset('images/logo.jpg') }}" width="115" height="55" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto admin-nav-link">
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/home"><i class="fa fa-home nav-icon"></i>&nbsp;Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders')}}"><i class="fa fa-shopping-cart nav-icon"></i>&nbsp;Orders</a>
                        </li>
                        <li class="nav-item">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="customersDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-users nav-icon"></i>&nbsp;Customers <request-badge></request-badge>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="customersDropdown">
                                    <a class="dropdown-item" href="/admin/customers">Customers</a>
                                    <a class="dropdown-item" href="/admin/customers/cancellation-requests">Cancellation Requests <cancel-request-badge></cancel-request-badge></a>
                                     <a class="dropdown-item" href="/admin/customers/return-requests">Return Requests <return-request-badge></return-request-badge></a>
                                </div>
                            </li>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/inventory"><i class="fa fa-list-alt nav-icon"></i>&nbsp;Inventory</a>
                        </li>
                        <li class="nav-item">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-file nav-icon"></i>&nbsp;Reports
                                </a>
                                <div class="dropdown-menu" aria-labelledby="reportsDropdown">
                                    
                                    <a class="dropdown-item" href="/admin/reports/sales">Sales Report</a>
                                    <a class="dropdown-item" href="/admin/reports/inventory">Inventory Report</a>
                                    <a class="dropdown-item" href="/admin/reports/best-selling">Best Selling Report</a>
                                    <a class="dropdown-item" href="/admin/reports/customer-list">Customer List</a>
                                </div>
                            </li>
                        </li>
                        <li class="nav-item">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-gear nav-icon"></i>&nbsp;Maintenance
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/admin/categories">Categories</a>
                                    <a class="dropdown-item" href="/admin/products">Products</a>
                                    <a class="dropdown-item" href="/admin/bank-account">Bank Account</a>
                                    <a class="dropdown-item" href="/admin/delivery-fee">Delivery fee</a>
                                    <a class="dropdown-item" href="/admin/reason">Reason</a>
                                    <a class="dropdown-item" href="/admin/users">Users</a>
                                </div>
                            </li>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/user-logs"><i class="fa fa-users nav-icon"></i>&nbsp;User Logs</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav admin-nav-link">
                       {{--  <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);"><user-name :admin="{{Auth::guard('admin')->user()}}"></user-name></a>
                        </li> --}}
                        <li class="nav-item">
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" id="usernameDropdown" role="button" data-toggle="dropdown"><i class="fa fa-user nav-icon"></i> {{Auth::guard('admin')->user()->username}}</a>
                                <div class="dropdown-menu" aria-labelledby="usernameDropdown">
                                    <a class="dropdown-item" href="/admin/user/edit-information">Edit information</a>
                                </div>
                            </li>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/logout"><i class="fa fa-sign-out nav-icon"></i>&nbsp;Logout</a>
                        </li>
                       
                    </ul>
                    
                </div>
            </nav>
            <div class="container-fluid mt-4" id="admin-main-content">
            @yield('content')
            </div>
         
           {{--  <div id="admin-footer">
                <center>{{ config('app.name') }} &copy; {{ date('Y') }}</center>   
            </div> --}}
                    
        </div>
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('vendor/popper/dist/umd/popper.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
        
      
  {{--      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> --}}
    </body>
</html>
