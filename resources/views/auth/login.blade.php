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
              <h3>Sign In to </h3>
              <p class="mb-4"><strong>{{ config('app.name', 'Laravel') }}</strong></p>
            </div>
            <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}" onsubmit='show()'>
                @csrf
              <div class="form-group first">
                <label for="email">Email</label>
                <input  class="form-control" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email Address" required autofocus>
              
              </div>
              <div class="form-group last mb-4">
                <label for="password">Password</label>
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="********" name="password" required>
               
              </div>
              
              <div class="d-flex mb-5 align-items-center">
                <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                  <input type="checkbox" checked="checked"/>
                  <div class="control__indicator"></div>
                </label>
                <br>
                <span class="ml-auto"><a  href="{{ route('password.request') }}" onclick='show()' class="forgot-pass">Forgot Password</a></span> 
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
