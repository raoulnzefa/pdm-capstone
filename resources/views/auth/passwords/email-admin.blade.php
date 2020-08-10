@extends('backend.login')



@section('content')
<div class="card card-login mx-auto mt-3">
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
            <label for="email" class="ifg-label">Email:</label>
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary ifg-btn"><i class="fa fa-sign-in"></i>&nbsp;Send Password Reset Link</button>
        </div>
    </form> 
</div><!-- card-body  -->
</div> {{-- card --}}
@endsection