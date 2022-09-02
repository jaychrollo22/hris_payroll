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
                    <h5 class="card-text mt-2">Employee Code : {{auth()->user()->employee->employee_code}}</h5>
                    <img class='border' src='{{URL::asset(auth()->user()->employee->signature)}}' onerror="this.src='{{URL::asset('/images/signature.png')}}';" height='100px;' width='225px;'> <br>
                    <button  class="btn btn-primary btn-sm mt-3" data-toggle="modal" data-target="#uploadAvatar">
                        Upload Avatar
                    </button>
                    <button  class="btn btn-info btn-sm mt-3" data-toggle="modal" data-target="#uploadSignature">
                        Upload Signature
                    </button>
                </div>
              </div>
              <div class="card mt-3">
                <div class="card-body text-center">
                    <div class="card-body text-left">
                        <div class="template-demo">
                            <div class='row'>
                                <div class='col-md-12 text-center'>
                                    <strong><h3><i class="fa fa-calendar" aria-hidden="true"></i> Schedule</h3></strong>
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                </div>
                                <div class='col-md-3'>
                                    <small>Start Time</small>
                                </div>
                                <div class='col-md-3'>
                                    <small>End Time</small>
                                </div>
                                <div class='col-md-3'>
                                    <small>Total hours</small>
                                </div>
                            </div>
                            @foreach($user->employee->ScheduleData as $schedule)
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>{{$schedule->name}}</small>
                                </div>
                                <div class='col-md-3'>
                                    <small>{{$schedule->time_in_from}}</small> <br>
                                    <small>{{$schedule->time_in_to}}</small> 

                                </div>
                                <div class='col-md-3'>
                                    <small>{{$schedule->time_out_from}}</small> <br>
                                    <small>{{$schedule->time_out_to}}</small> 
                                </div>
                                <div class='col-md-3'>
                                    <small>{{number_format($schedule->working_hours,1)}} </small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-lg-5 grid-margin">
              <div class="card">
                <div class="card-body text-left">
                    <div class="template-demo">
                        <div class='row m-2'>
                            <div class='col-md-12 text-center'>
                                <strong><h3><i class="fa fa-user" aria-hidden="true"></i> Personal Information</h3></strong>
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
                                @php
                                    $d1 = new DateTime(auth()->user()->employee->birth_date);
                                    $d2 = new DateTime();
                                    $diff = $d2->diff($d1);
                                @endphp
                                <small> Date :  {{date('F d, Y',strtotime(auth()->user()->employee->birth_date))}} : {{$diff->y}} Years Old</small><br>
                                <small> Place :  {{auth()->user()->employee->birth_place}} </small>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="card mt-3">
                <div class="card-body text-left">
                    <div class="template-demo">
                        <div class='row m-2'>
                            <div class='col-md-12 text-center'>
                                <strong><h3><i class="fa fa-user-plus" aria-hidden="true"></i> Employment Information</h3></strong>
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>  Email </small>
                            </div>
                            <div class='col-md-9'>
                                {{auth()->user()->email}}
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
                                <small>  Date Hired </small>
                            </div>
                            <div class='col-md-3'>
                                {{date('M d, Y',strtotime(auth()->user()->employee->original_date_hired))}}
                            </div>
                            <div class='col-md-6'>
                                @php
                                    $date_from = new DateTime(auth()->user()->employee->original_date_hired);
                                    $date_diff = $date_from->diff(new DateTime(date('Y-m-d')));
                                @endphp
                                {{$date_diff->format('%y Year %m months %d days')}}
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>SSS</small>
                            </div>
                            <div class='col-md-3'>
                                <small>{{auth()->user()->employee->sss_number}}</small>
                            </div>
                            <div class='col-md-3'>
                                <small>HDMF</small>
                            </div>
                            <div class='col-md-3'>
                                <small>{{auth()->user()->employee->hdmf_number}}</small>
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>PHILHEALTH</small>
                            </div>
                            <div class='col-md-3'>
                                <small>{{auth()->user()->employee->phil_number}}</small>
                            </div>
                            <div class='col-md-3'>
                                <small>TIN</small>
                            </div>
                            <div class='col-md-3'>
                                <small>{{auth()->user()->employee->tax_number}}</small>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              @if(auth()->user()->employee->payment_info)
              <div class="card mt-3">
                <div class="card-body text-left">
                    <div class="template-demo">
                        <div class='row m-2'>
                            <div class='col-md-12 text-center'>
                                <strong><h3><i class="fa fa-money" aria-hidden="true"></i> Payment Information</h3></strong>
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>Payment Period</small>
                            </div>
                            <div class='col-md-3'>
                                {{auth()->user()->employee->payment_info->payment_period}}
                            </div>
                            <div class='col-md-3'>
                                <small>Payment Type</small>
                            </div>
                            <div class='col-md-3'>
                                {{auth()->user()->employee->payment_info->payment_type}}
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>Bank Name</small>
                            </div>
                            <div class='col-md-3'>
                                {{auth()->user()->employee->payment_info->bank_name}}
                            </div>
                            <div class='col-md-3'>
                                <small>Account Number</small>
                            </div>
                            <div class='col-md-3'>
                                {{auth()->user()->employee->payment_info->account_number}}
                            </div>
                        </div>
                        <div class='row  m-2 border-bottom'>
                            <div class='col-md-3'>
                                <small>Rate</small>
                            </div>
                            <div class='col-md-3'>
                                <a href="#" data-toggle="modal" onclick='reset_data();' data-target="#rateData">********</a>
                            </div>
                        </div>
                        
                    </div>
                </div>
              </div>
              @endif
            </div>
            <div class="col-lg-3 grid-margin">
            @if($user->employee->immediate_sup != null)
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
                                    <small><img class="rounded-circle" style='width:38px;height:38px;' src='{{$user->employee->immediate_sup_data->employee->avatar}}' onerror="this.src='{{URL::asset('/images/no_image.png')}}';"></small>
                                    <div>
                                      <p class="text-info mb-1">{{$user->employee->immediate_sup_data->name}}</p>
                                      <p class="mb-0">{{$user->employee->immediate_sup_data->employee->department->name}}</p>
                                      {{-- <small>August 16</small> --}}
                                    </div>
                                  </div>
                                </li>
                              </ul>
                        </div>
                    </div>
                </div>
              </div>
              @endif
              @if(!$user->approvers)
              <div class="card  mt-3">
                <div class="card-body text-left">
                    <div class="template-demo">
                        <div class='row m-2'>
                            <div class='col-md-12 text-center'>
                                <strong><h4>HR Forms Approver</h4></strong>
                            </div>
                            <ul class="icon-data-list">
                                @foreach($user->approvers as $approver)
                                <li >
                                  <div class="d-flex  align-self-center">
                                    <p class='align-self-center mr-3'>lvl {{$approver->level}}</p>
                                    <small><img class="rounded-circle" style='width:38px;height:38px;' src='{{$approver->approver_data->employee->avatar}}' onerror="this.src='{{URL::asset('/images/no_image.png')}}';"></small>
                                    <div>
                                      <p class="text-info mb-1">{{$approver->approver_data->name}}</p>
                                      <p class="mb-0">{{$approver->approver_data->employee->department->name}}</p>
                                      {{-- <small>August 16</small> --}}
                                    </div>
                                  </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
              </div>
              @endif
              @if(!empty($user->subbordinates))
              <div class="card mt-3">
                <div class="card-body text-left">
                    <div class="template-demo">
                        <div class='row m-2'>
                            <div class='col-md-12 text-center'>
                                <strong><h4>Subordinates</h4></strong>
                            </div>
                            <ul class="icon-data-list">
                                @foreach($user->subbordinates as $subbordinate)
                                <li>
                                  <div class="d-flex  align-self-center">
                                    <small><img class="rounded-circle" style='width:38px;height:38px;' src='{{$subbordinate->avatar}}' onerror="this.src='{{URL::asset('/images/no_image.png')}}';"></small>
                                    <div>
                                      <p class="text-info mb-1">{{$subbordinate->first_name}} {{$subbordinate->last_name}}</p>
                                      <p class="mb-0">{{$subbordinate->position}}</p>
                                    </div>
                                  </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
              </div>
              @endif
            </div>
        </div>
    </div>
</div>
@include('users.upload_avatar')
@include('users.upload_signature')
@include('users.view_salary')
@endsection
