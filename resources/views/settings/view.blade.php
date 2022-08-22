@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Settings</h4>
                    <div class="form-card">
                        <hr>
                        <div class="form-group row">
                          <div class="col-lg-6 align-self-center text-center">
                            
                              <img id='avatar' src='@if($settings != null){{URL::asset($settings->where('icon','!=',null)->first()->icon)}}@endif' onerror="this.src='{{URL::asset('/images/icon.png')}}';"  class=" circle-border m-b-md border" alt="profile" height='130px;' width='130px;'> <br>
                              <i><small>SIZE : 130px x 130px</small></i>
                              <br>
                            <button title="Upload image file" data-toggle="modal" data-target="#uploadIcon"  class="btn btn-primary btn-sm mt-2">
                                Change Icon
                            </button>
                            
                          </div>
                          <div class="col-lg-6 text-center">
                            <img id='signature' src='@if($settings != null){{URL::asset($settings->logo)}}@endif' onerror="this.src='{{URL::asset('/images/obanana_brand.png')}}';"  class="rounded-square circle-border m-b-md border" alt="profile" height='130px;' width='431px;'> <br>
                            <i><small>SIZE : 130px x 431px</small></i> <br>
                            <button title="Upload image file"  data-toggle="modal" data-target="#uploadLogo"  class="btn btn-info btn-sm mt-2">
                                Change Brand Logo
                            </button>
                          </div>

                      </div>
                </div>
              </div>
            </div>
          </div>
        
        </div>
    </div>
</div>
@include('settings.upload_icon')
@include('settings.upload_logo')
@endsection
