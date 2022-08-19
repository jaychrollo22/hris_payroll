@extends('layouts.app')

@section('content')
<div class="content">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <img src="{{asset('login_css/images/present.png')}}" alt="Image" class="img-fluid">
        </div>
        <div class="col-md-6 ">
          <div class="row justify-content-center">
            <div class="col-md-8">
              <div class="mb-4">
              <h3>Reset Password </h3>
              <p class="mb-4"><strong>{{ config('app.name', 'Laravel') }}</strong></p>
            </div>
            <form method="POST" action="{{ route('password.update') }}" aria-label="{{ __('Login') }}" onsubmit='show()'>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
              <div class="form-group first">
                <label for="email">Email</label>
                <input  class="form-control" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email Address" required autofocus>
              
              </div>
              <div class="form-group last mb-4">
                <label for="password">Password</label>
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="********" name="password" required>
               
              </div>
              <div class="form-group last mb-4">
                <label for="confirm_password">Confirm Password</label>
                <input id="confirm_password" type="password" class="form-control{{ $errors->has('confirm_password') ? ' is-invalid' : '' }}" placeholder="********" name="password_confirmation" required>
               
              </div>
            
              @if($errors->any())
                    <div class="form-group alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <strong>{{$errors->first()}}</strong>
                    </div>
                @endif
              <input type="submit" value="Log In" class="btn text-white btn-block btn-primary">

              {{-- <span class="d-block text-left my-4 text-muted text-right"> or sign in with</span>
              
              <div class="social-login text-right">
                <a href="#" class="facebook">
                  <span class="icon-facebook mr-3"></span> 
                </a>
                <a href="#" class="twitter">
                  <span class="icon-twitter mr-3"></span> 
                </a>
                <a href="#" class="google">
                  <span class="icon-google mr-3"></span> 
                </a>
              </div> --}}
            </form>
            </div>
          </div>
          
        </div>
        
      </div>
    </div>
  </div>

@endsection
