@extends('frontend.frontend_template')

@section('store_header')
<nav class="navbar navbar-expand-sm bg-dark navbar-dark" id="partial-header">
    <div class="container-fluid">
        <div class="navbar-brand">
        <img src="{{ (!is_null($company)) ? $company->logo_url : asset('images/WEBFINITY.jpg') }}" width="125" height="60" alt="{{ (!is_null($company)) ? $company->name : 'Webfinity' }}">
        </div>
        <a href="/login" class="nav-link text-light cool-link mr-sm-2"><i class="fa fa-chevron-left"></i> BACK</a>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('frontend_homepage') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Forgot Password</li>
              </ol>
            </nav>
        </div>  
    </div><!-- row 1 -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header ifg-card-header text-center">
                    Forgot Password
                </div>
                <div class="card-body">
                    <p>Fill in your email below to request a reset password. An email will be sent to the email address below containing a link to verify your email address.</p>
                     @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <customer-forgot-password :old_email="{{ json_encode(old('email')) }}"></customer-forgot-password>
                </div>
            </div>
        </div>
    </div>    
</div>

{{-- <div class="card card-login mx-auto mt-3">
<div class="card-header">Reset Password</div>
<div class="card-body">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form method="POST" action="{{ route('admin.password.email') }}">
        @csrf
        <div class="form-group">
            <label for="email">{{ __('Email') }}</label>
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block">Send Password Reset Link</button>
        </div>
    </form> 
</div><!-- card-body  -->
</div>  --}}
@endsection