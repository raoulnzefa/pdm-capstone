@extends('backend.login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card card-login mt-3">
            <div class="card-header">Login</div>
            <form method="POST" action="{{ route('admin.login') }}">
            <div class="card-body">   

                @if (Session::has('login_failed'))      
                <p class="alert alert-danger"><b><i class="fa fa-warning"></i></b>&nbsp;{{ Session::get('login_failed') }}</p>
                @endif
                
                    @csrf
                    <div class="form-group">
                        <label class="ifg-label" for="email">Username:</label>
                        <input id="email" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus placeholder="Enter your username">
                    </div>

                        <div class="form-group">
                          <label class="ifg-label" for="password">Password:</label>
                          <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Enter your password" required>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif

                        </div>

                        <div class="form-group">
                            
                        </div>
                    
            </div><!-- card-body  -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-block">Login</button> 
            </div>
            </form> 
            </div> {{-- card --}}
    </div>
</div>
@endsection