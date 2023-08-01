@extends('layout.master2')

@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-12 pr-md-0">
            <div class="auth-left-wrapper text-center">
                <img width="100%" src="{{ URL::asset('assets/images/logo.png')}}" class="img-fluid" />
            </div>
          </div>
          <div class="col-md-12 pl-md-0">
            <div class="auth-form-wrapper px-4 py-5">
            <div class="text-center">
            </div>
              <form class="forms-sample" method="POST" action="{{ url('post-login') }}">
              {{ csrf_field() }}
                <div class="form-group">
                  <label for="exampleInputEmail1">Email address</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Password</label>
                  <input type="password" class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword1" autocomplete="current-password" placeholder="Password" name="password" required>                    
                  @error('password')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                <!-- <div class="form-check form-check-flat form-check-primary">
                  <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                  </label>
                </div> -->
                <div class="mt-3">
                  <button type="submit" class="btn btn-primary mr-2 mb-2 mb-md-0">
                      {{ __('Login') }}
                  </button>                  
                  @if(Session::has('error'))    
                      <div class="alert alert-danger text-center mt-3">{{ Session::get('error') }}</div>
                  @endif
                </div>
                <!-- <a href="{{ url('/register') }}" class="d-block mt-3 text-muted">Not a user? Sign up</a> -->
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection