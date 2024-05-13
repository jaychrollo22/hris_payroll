@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-4 grid-margin ">
                <div class="card">
                    <div class="card-body text-center">
                        <img class="rounded-circle" style='width:170px;height:170px;' src='{{URL::asset($user->employee->avatar)}}' onerror="this.src='{{URL::asset('/images/no_image.png')}}';">

                        <h3 class="card-text mt-3">{{$user->employee->first_name}} @if($user->employee->middle_initial != null){{$user->employee->middle_initial}}.@endif {{$user->employee->last_name}}</h3>
                        <h4 class="card-text mt-2">{{$user->employee->position}}</h4>
                        <h5 class="card-text mt-2">Biometric Code : {{$user->employee->employee_number}}</h5>
                        {{-- <h5 class="card-text mt-2">Employee Code : {{$user->employee->employee_code}}</h5> --}}
                        @if($user->employee->signature)
                            <img class='border' src='{{URL::asset($user->employee->signature)}}' onerror="this.src='{{URL::asset('/images/signature.png')}}';" height='100px;' width='auto;'> <br>
                        @endif
                        <button class="btn btn-primary btn-sm mt-3" data-toggle="modal" data-target="#uploadAvatar">
                            Upload Avatar
                        </button>
                        <button class="btn btn-info btn-sm mt-3" data-toggle="modal" data-target="#uploadSignature">
                            Upload Signature
                        </button>
                        <a class="btn btn-warning btn-sm mt-3" href='{{url("print-id/".$user->employee->id)}}' target="_blank">
                            Print ID
                        </a>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-body text-center">
                        <div class="card-body text-left">
                            <div class="template-demo">
                                <div class='row'>
                                    <div class='col-md-12 text-center'>
                                        <strong>
                                            <h3><i class="fa fa-calendar" aria-hidden="true"></i> Schedule</h3>
                                        </strong>
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
                                    <strong>
                                        <h3><i class="fa fa-user" aria-hidden="true"></i> Personal Information
                                            @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                                <button class="btn btn-icon btn-info btn-xs" title="Edit Information" data-toggle="modal" data-target="#editInfo"><i class="fa fa-pencil"></i></button>
                                            @endif
                                        </h3>
                                    </strong>
                                </div>
                            </div>
                            <div class='row m-2 border-bottom'>
                                <div class='col-md-3 '>
                                    <small>Nickname </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->nick_name}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small> Full Name </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->first_name}} @if($user->employee->middle_initial != null){{$user->employee->middle_initial}}.@endif {{$user->employee->last_name}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small> Email </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->personal_email}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small> Phone</small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->personal_number}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>Marital Status </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->marital_status}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>Religion </small>
                                </div>
                                <div class='col-md-3'>
                                    {{$user->employee->religion}}
                                </div>
                                <div class='col-md-3'>
                                    <small>Gender </small>
                                </div>
                                <div class='col-md-3'>
                                    {{$user->employee->gender}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>Address </small>
                                </div>
                                <div class='col-md-9'>
                                    <small> Present : {{$user->employee->present_address}} </small><br>
                                    <small> Permanent : {{$user->employee->permanent_address}} </small>
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>Birth </small>
                                </div>
                                <div class='col-md-9'>
                                    @php
                                    $d1 = new DateTime($user->employee->birth_date);
                                    $d2 = new DateTime();
                                    $diff = $d2->diff($d1);
                                    @endphp
                                    <small> Date : {{date('F d, Y',strtotime($user->employee->birth_date))}} : {{$diff->y}} Years Old</small><br>
                                    <small> Place : {{$user->employee->birth_place}} </small>
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
                                    <strong>
                                        <h3><i class="fa fa-user-plus" aria-hidden="true"></i> Contact Person (In case of Emergency)
                                            @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                                <button class="btn btn-icon btn-info btn-xs" title="Edit Contact Person" data-toggle="modal" data-target="#editContactInfo"><i class="fa fa-pencil"></i></button>
                                            @endif
                                        </h3>
                                    </strong>
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small> Contact Person </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->contact_person ? $user->employee->contact_person->name : ""}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small> Contact Number </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->contact_person ? $user->employee->contact_person->contact_number : ""}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small> Relation </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->contact_person ? $user->employee->contact_person->relation : ""}}
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
                                    <strong>
                                        <h3><i class="fa fa-user-plus" aria-hidden="true"></i> Beneficiaries
                                            @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                                <button class="btn btn-icon btn-info btn-xs" title="Edit Beneficiaries" data-toggle="modal" data-target="#editBeneficiaries"><i class="fa fa-pencil"></i></button>
                                            @endif
                                        </h3>
                                    </strong>
                                </div>
                            </div>
                            
                            @foreach($user->employee->beneficiaries as $key => $value)
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>{{$value->relation}}</small>
                                </div>
                                <div class='col-md-9'>
                                    <small>{{$value->first_name . ' ' . $value->last_name}}</small>
                                </div>
                            </div>
                            @endforeach                            
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body text-left">
                        <div class="template-demo">
                            <div class='row m-2'>
                                <div class='col-md-12 text-center'>
                                    <strong>
                                        <h3><i class="fa fa-user-plus" aria-hidden="true"></i> Employment Information 
                                            @if (checkUserPrivilege('employees_edit',auth()->user()->id) == 'yes')
                                                <button class="btn btn-icon btn-info btn-xs" title="Edit Employee Information" data-toggle="modal" data-target="#editEmpInfo"><i class="fa fa-pencil"></i></button>
                                            @endif
                                        </h3>
                                    </strong>
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small> Email </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->email}}
                                </div>
                            </div>
                            <div class='row m-2 border-bottom'>
                                <div class='col-md-3 '>
                                    <small>Company </small>
                                </div>
                                <div class='col-md-9'>
                                    @if($user->employee->company) {{$user->employee->company->company_name}} @endif
                                </div>
                            </div>
                            <div class='row m-2 border-bottom'>
                                <div class='col-md-3 '>
                                    <small>Deparment </small>
                                </div>
                                <div class='col-md-9'>
                                    @if($user->employee->department) {{$user->employee->department->name}} @endif
                                </div>
                            </div>
                            <div class='row m-2 border-bottom'>
                                <div class='col-md-3 '>
                                    <small>Location </small>
                                </div>
                                <div class='col-md-9'>
                                    @if($user->employee->location) {{$user->employee->location}} @endif
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small> Classification </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->classification_info ? $user->employee->classification_info->name : ""}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small> Level </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->level_info ? $user->employee->level_info->name : "" }}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small> Date Hired </small>
                                </div>
                                <div class='col-md-3'>
                                    {{date('M d, Y',strtotime($user->employee->original_date_hired))}}
                                </div>
                                <div class='col-md-6'>
                                    @php
                                    $date_from = new DateTime($user->employee->original_date_hired);
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
                                    <small>{{$user->employee->sss_number}}</small>
                                </div>
                                <div class='col-md-3'>
                                    <small>HDMF</small>
                                </div>
                                <div class='col-md-3'>
                                    <small>{{$user->employee->hdmf_number}}</small>
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>PHILHEALTH</small>
                                </div>
                                <div class='col-md-3'>
                                    <small>{{$user->employee->phil_number}}</small>
                                </div>
                                <div class='col-md-3'>
                                    <small>TIN</small>
                                </div>
                                <div class='col-md-3'>
                                    <small>{{$user->employee->tax_number}}</small>
                                </div>
                            </div>

                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small> BANK NAME </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->bank_name}}
                                </div>
                            </div>

                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small> ACCOUNT NUMBER </small>
                                </div>
                                <div class='col-md-9'>
                                    {{$user->employee->bank_account_number}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @if($user->employee->payment_info)
                <div class="card mt-3">
                    <div class="card-body text-left">
                        <div class="template-demo">
                            <div class='row m-2'>
                                <div class='col-md-12 text-center'>
                                    <strong>
                                        <h3><i class="fa fa-money" aria-hidden="true"></i> Payment Information</h3>
                                    </strong>
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>Payment Period</small>
                                </div>
                                <div class='col-md-3'>
                                    {{$user->employee->payment_info->payment_period}}
                                </div>
                                <div class='col-md-3'>
                                    <small>Payment Type</small>
                                </div>
                                <div class='col-md-3'>
                                    {{$user->employee->payment_info->payment_type}}
                                </div>
                            </div>
                            <div class='row  m-2 border-bottom'>
                                <div class='col-md-3'>
                                    <small>Bank Name</small>
                                </div>
                                <div class='col-md-3'>
                                    {{$user->employee->bank_name}}
                                </div>
                                <div class='col-md-3'>
                                    <small>Account Number</small>
                                </div>
                                <div class='col-md-3'>
                                    {{$user->employee->bank_account_number}}
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
                @endif --}}
            </div>
</div>
</div>
</div>
@include('employees.upload_avatar')
@include('employees.upload_signature')
@include('employees.edit_info')
@include('employees.edit_employee_info')
@include('employees.edit_contact_info')
@include('employees.edit_beneficiaries')
@endsection
