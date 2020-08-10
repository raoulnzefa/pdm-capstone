@extends('frontend.frontend_template')

@section('store_header')
<nav class="navbar navbar-expand-sm bg-dark navbar-dark" id="partial-header">
    <div class="container-fluid">
        <div class="navbar-brand">
        <img src="{{ asset('images/logo.jpg') }}" width="125" height="60" alt="INFINITY FIGHTGEAR LOGO">
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
                <li class="breadcrumb-item active" aria-current="page">Reset Password</li>
              </ol>
            </nav>
        </div>  
    </div><!-- row 1 -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="ifg-header mb-3">Reset Password</h4>
                    <customer-reset-password token="{{ $token }}"></customer-reset-password>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="card card-login mx-auto mt-3">
<div class="card-header">Reset Password</div>
<div class="card-body">   

    <form method="POST" action="{{ route('admin.password.request') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
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
            <label for="password">Password</label>
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
            @if ($errors->has('password'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label for="password-confirm">Confirm Password</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Reset Password</button> 
        </div>
        </form> 
</div>card-body
</div> --}}
@endsection