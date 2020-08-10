@extends('backend.login')

@section('content')
<div class="card card-login mx-auto mt-3">
<div class="card-header">Reset Password</div>
<div class="card-body">   

	@if (Session::has('login_failed'))      
    <p class="alert alert-danger"><b><i class="fa fa-warning"></i></b>&nbsp;{{ Session::get('login_failed') }}</p>
	@endif
    <form method="POST" action="{{ route('admin.password.request') }}">
        @csrf
        <div class="form-group">
            <label for="email">{{ __('Email') }}</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
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
          <input id="password-confirm" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password_confirmation" required>
            @if ($errors->has('password_confirmation'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif

        </div>

        <div class="form-group">
          	<button type="submit" class="btn btn-primary btn-block">Reset Password
            </button>     
        </div>
    </form> 
</div><!-- card-body  -->
</div> {{-- card --}}
@endsection