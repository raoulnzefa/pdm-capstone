@extends('backend.login')

@section('content')
<div class="card card-login mx-auto mt-3">
<div class="card-header">Reset Password</div>
<div class="card-body">   
	{{-- @if (Session::has('login_failed'))      
    <p class="alert alert-danger"><b><i class="fa fa-warning"></i></b>&nbsp;{{ Session::get('login_failed') }}</p>
	@endif --}}
    <form method="POST" action="{{ route('admin.password.email') }}">
        @csrf
        <div class="form-group">
            <label for="email">{{ __('Email') }}</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block">Send Password Reset Link</button>
        </div>
    </form> 
</div><!-- card-body  -->
</div> {{-- card --}}
@endsection