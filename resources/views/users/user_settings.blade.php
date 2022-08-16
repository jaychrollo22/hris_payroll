@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-4 grid-margin ">
              <div class="card">
                <div class="card-body text-center">
                    <img class="rounded-circle" style='width:170px;height:170px;' src='{{URL::asset(auth()->user()->employee->avatar)}}' onerror="this.src='{{URL::asset('/images/no_image.png')}}';">
                    
                    <h3 class="card-text mt-3">{{auth()->user()->employee->first_name}} {{auth()->user()->employee->last_name}}</h3>
                    <h4 class="card-text mt-2">{{auth()->user()->employee->position}}</h4>
                    <h5 class="card-text mt-2">Biometric Code : {{auth()->user()->employee->employee_number}}</h5>
                    <h5 class="card-text mt-2">Employee Code : {{auth()->user()->employee->employee_number}}</h5>
                    <img class='border' src='{{URL::asset(auth()->user()->employee->signature)}}' onerror="this.src='{{URL::asset('/images/signature.png')}}';" height='100px;' width='225px;'> <br>
                    <button  class="btn btn-primary btn-sm mt-3" data-toggle="modal" data-target="#uploadAvatar">
                        Upload Avatar
                    </button>
                    <button  class="btn btn-info btn-sm mt-3" data-toggle="modal" data-target="#uploadSignature">
                        Upload Signature
                    </button>
                </div>
              </div>
            </div>
            <div class="col-lg-5 grid-margin">
              <div class="card">
                <div class="card-body text-left">
                    <div class="template-demo">
                        <div class='row m-2'>
                            <div class='col-md-12 text-center'>
                                <strong><h2>Personal Information</h2></strong>
                            </div>
                        </div>
                        <div class='row m-2 border-bottom'>
                            <div class='col-md-3 '>
                                <small>Nickname </small>
                            </div>
                            <div class='col-md-9'>
                                {{auth()->user()->employee->nick_name}}
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small> Full Name </small>
                            </div>
                            <div class='col-md-9'>
                                {{auth()->user()->employee->first_name}} {{auth()->user()->employee->last_name}}
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>  Email </small>
                            </div>
                            <div class='col-md-9'>
                                {{auth()->user()->employee->personal_email}}
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small> Phone</small>
                            </div>
                            <div class='col-md-9'>
                                {{auth()->user()->employee->personal_number}}
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>Marital Status </small>
                            </div>
                            <div class='col-md-9'>
                                {{auth()->user()->employee->marital_status}}
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>Religion </small>
                            </div>
                            <div class='col-md-3'>
                                {{auth()->user()->employee->religion}} 
                            </div>
                            <div class='col-md-3'>
                                <small>Gender </small>
                            </div>
                            <div class='col-md-3'>
                                {{auth()->user()->employee->gender}} 
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>Address </small>
                            </div>
                            <div class='col-md-9'>
                                <small> Present :  {{auth()->user()->employee->present_address}} </small><br>
                                <small> Permanent :  {{auth()->user()->employee->permanent_address}} </small>
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>Birth </small>
                            </div>
                            <div class='col-md-9'>
                                <small> Date :  {{date('F d, Y',strtotime(auth()->user()->employee->birth_date))}} </small><br>
                                <small> Place :  {{auth()->user()->employee->birth_place}} </small>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 grid-margin">
                
              <div class="card">
                <div class="card-body text-left">
                    <div class="template-demo">
                        <div class='row m-2'>
                            <div class='col-md-12 text-center'>
                                <strong><h4>Immediate Supervisor</h4></strong>
                            </div>
                            <ul class="icon-data-list">
                                <li class=''>
                                  <div class="d-flex  align-self-center">
                                    <img src="body_css/images/faces/face1.jpg" alt="user">
                                    <div>
                                      <p class="text-info mb-1">Renz Christian Cabato</p>
                                      <p class="mb-0">Department</p>
                                      {{-- <small>August 16</small> --}}
                                    </div>
                                  </div>
                                </li>
                               
                              </ul>
                        </div>
                    </div>
                </div>
              </div>
              <div class="card  mt-3">
                <div class="card-body text-left">
                    <div class="template-demo">
                        <div class='row m-2'>
                            <div class='col-md-12 text-center'>
                                <strong><h4>HR Forms Approver</h4></strong>
                            </div>
                            <ul class="icon-data-list">
                                <li class=''>
                                  <div class="d-flex  align-self-center">
                                    <p class='align-self-center mr-3'>1</p>
                                    <img src="body_css/images/faces/face1.jpg" alt="user">
                                    <div>
                                      <p class="text-info mb-1">Renz Christian Cabato</p>
                                      <p class="mb-0">Department</p>
                                      {{-- <small>August 16</small> --}}
                                    </div>
                                  </div>
                                </li>
                               
                              </ul>
                        </div>
                    </div>
                </div>
              </div>
              <div class="card mt-3">
                <div class="card-body text-left">
                    <div class="template-demo">
                        <div class='row m-2'>
                            <div class='col-md-12 text-center'>
                                <strong><h4>Subbordinate</h4></strong>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 grid-margin  stretch-card">
              <div class="card">
                <div class="card-body text-left">
                    <div class="template-demo">
                        <div class='row m-2'>
                            <div class='col-md-12 text-center'>
                                <strong><h3>Employment Information</h3></strong>
                            </div>
                        </div>
                        <div class='row m-2 border-bottom'>
                            <div class='col-md-3 '>
                                <small>Deparment </small>
                            </div>
                            <div class='col-md-9'>
                                @if(auth()->user()->employee->department) {{auth()->user()->employee->department->name}} @endif
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small> Classification </small>
                            </div>
                            <div class='col-md-9'>
                                {{auth()->user()->employee->classification}} 
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>  Level </small>
                            </div>
                            <div class='col-md-9'>
                                {{auth()->user()->employee->level}}
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small> Phone</small>
                            </div>
                            <div class='col-md-9'>
                                {{auth()->user()->employee->personal_number}}
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>Marital Status </small>
                            </div>
                            <div class='col-md-9'>
                                {{auth()->user()->employee->marital_status}}
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>Religion </small>
                            </div>
                            <div class='col-md-3'>
                                {{auth()->user()->employee->religion}} 
                            </div>
                            <div class='col-md-3'>
                                <small>Gender </small>
                            </div>
                            <div class='col-md-3'>
                                {{auth()->user()->employee->gender}} 
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>Address </small>
                            </div>
                            <div class='col-md-9'>
                                <small> Present :  {{auth()->user()->employee->present_address}} </small><br>
                                <small> Permanent :  {{auth()->user()->employee->permanent_address}} </small>
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>Birth </small>
                            </div>
                            <div class='col-md-9'>
                                <small> Date :  {{date('F d, Y',strtotime(auth()->user()->employee->birth_date))}} </small><br>
                                <small> Place :  {{auth()->user()->employee->birth_place}} </small>
                            </div>
                        </div>
                   
                    </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@include('users.upload_avatar')
@include('users.upload_signature')
@endsection
