@extends('backend.login')

@section('content')
<div class="card card-login mx-auto mt-3">
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
</div><!-- card-body  -->
</div> {{-- card --}}
@endsection