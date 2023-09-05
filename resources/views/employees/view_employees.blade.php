@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Employee Classification(Active)</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees_classification as $item)
                                    <tr>
                                        <td>{{$item->classification_info ? $item->classification_info->name : $item->classification}}</td>
                                        <td>
                                            @if($item->classification_info)
                                                <a href="{{url('/employees?classification=' . $item->classification_info->id)}}">{{$item->total}}</a>
                                            @else
                                                <a href="{{url('/employees?classification=' . 'N/A' )}}">{{$item->total}}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <thead>
                                    <tr>
                                        <th>Employee Gender(Active)</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees_gender as $item)
                                    <tr>
                                        <td>{{$item->gender ? $item->gender : ""}}</td>
                                        <td>
                                            @if($item->gender)
                                                <a href="{{url('/employees?gender=' . $item->gender)}}">{{$item->total}}</a>
                                            @else
                                                <a href="{{url('/employees?gender=' . 'N/A' )}}">{{$item->total}}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <table class="table table-hover">
                               
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-lg-2 grid-margin'>
                <div class="card card-tale">
                    <div class="card-body">
                        <div class="media">

                            <div class="media-body">
                                <h4 class="mb-4">For Clearance</h4>
                                <h2 class="card-text">0</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-lg-2 grid-margin'>
                <div class="card card-light-danger">
                    <div class="card-body">
                        <div class="media">

                            <div class="media-body">
                                <h4 class="mb-4">Cleared</h4>
                                <h2 class="card-text">0</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-lg-2'>
                <div class="card text-success">
                    <div class="card-body">
                        <div class="media">

                            <div class="media-body">
                                <h4 class="mb-4">Active</h4>
                                <h2 class="card-text">{{$employees_active}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Employees 
                            @if (checkUserPrivilege('employees_add',auth()->user()->id) == 'yes')
                                <button type="button" class="btn btn-outline-success btn-icon-text btn-sm text-center" data-toggle="modal" data-target="#newEmployee"><i class="ti-plus btn-icon-prepend"></i></button>
                                @if(auth()->user()->id == '353' || auth()->user()->id == '1')
                                    <button type="button" class="btn btn-outline-warning btn-icon-text btn-sm text-center" data-toggle="modal" data-target="#uploadEmployee" title="Upload Employees"><i class="ti-arrow-up btn-icon-prepend"></i></button>
                                @endif
                                @if(auth()->user()->id == '1')
                                    <button type="button" class="btn btn-outline-primary btn-icon-text btn-sm text-center" data-toggle="modal" data-target="#uploadEmployeeRevertRate" title="Upload Rate Employees"><i class="ti-arrow-up btn-icon-prepend"></i></button>
                                @endif
                            @endif
                            @if (checkUserPrivilege('employees_export',auth()->user()->id) == 'yes')
                                <a href="/employees-export?company={{$company}}&department={{$department}}&status={{$status}}" class="btn btn-outline-danger btn-icon-text btn-sm text-center float-right" title="Export OTPMS"><i class="ti-arrow-down btn-icon-prepend"></i></a>
                                @if(auth()->user()->id == '660' || auth()->user()->id == '1' || auth()->user()->id == '1202')
                                    <a href="/associate-employees-export?company={{$company}}&department={{$department}}&status={{$status}}" class="btn btn-outline-warning btn-icon-text btn-sm text-center float-right mr-2" title="Export Employee Associates"><i class="ti-arrow-down btn-icon-prepend"></i></a>
                                @endif
                            @endif
                            @if (checkUserPrivilege('employees_export_hr',auth()->user()->id) == 'yes')
                                <a href="/employees-export-hr?company={{$company}}&department={{$department}}&status={{$status}}" class="btn btn-outline-primary btn-icon-text btn-sm text-center float-right mr-2" title="Export HR Details"><i class="ti-arrow-down btn-icon-prepend"></i></a>
                            @endif
                        </h4>

                        <h4 class="card-title">Filter</h4>
						<p class="card-description">
						<form method='get' onsubmit='show();' enctype="multipart/form-data">
							<div class=row>
                                <div class='col-md-2'>
									<div class="form-group">
                                        <input type="text" class="form-control" name="search" placeholder="Search Name / Biometric Code" value="{{$search}}">
                                    </div>
                                </div>
								<div class='col-md-2'>
									<div class="form-group">
										
                                        <select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company'>
                                            <option value="">-- Select Company --</option>
                                            @foreach($companies as $comp)
                                            <option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_name}} - {{$comp->company_code}}</option>
                                            @endforeach
                                        </select>
										
									</div>
								</div>
								<div class='col-md-2'>
									<div class="form-group">
										
                                        <select data-placeholder="Select Department" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='department'>
                                            <option value="">-- Select Department --</option>
                                            @foreach($departments as $dep)
                                            <option value="{{$dep->id}}" @if ($dep->id == $department) selected @endif>{{$dep->name}} - {{$dep->code}}</option>
                                            @endforeach
                                        </select>
										
									</div>
								</div>
								<div class='col-md-2'>
									<div class="form-group">
										
                                        <select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status'>
                                            <option value="">-- Select Status --</option>
                                            <option value="Active" @if ($status == 'Active') selected @endif>Active</option>
                                            <option value="Inactive" @if ($status == 'Inactive') selected @endif>Inactive</option>
                                            <option value="Resigned" @if ($status == 'Resigned') selected @endif>Resigned</option>
                                            <option value="Terminated" @if ($status == 'Terminated') selected @endif>Terminated</option>
                                        </select>
										
									</div>
								</div>
								<div class='col-md-2'>
									<button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="/employees" class="btn btn-warning">Reset Filter</a>
								</div>
							</div>
							
						</form>
						</p>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="datatableEmployee">
                                <thead>
                                    <tr>
                                        <th>Biometric Code</th>
                                        <th>User ID</th>
                                        <th>Employee</th>
                                        <th>Company</th>
                                        <th>Department</th>
                                        <th>Classification</th>
                                        <th>Immediate Supervisor</th>
                                        <th>Status </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees as $employee)
                                    <tr>
                                        
                                        <td>
                                            @if (checkUserPrivilege('employees_view',auth()->user()->id) == 'yes')
                                                <a href="/account-setting-hr/{{$employee->user_id}}" class="text-success btn-sm text-center">
                                                    <i class="ti-pencil btn-icon-prepend"></i>
                                                </a>
                                            @endif
                                            {{$employee->employee_number}}
                                        </td>
                                        <td>{{$employee->user_id}} </td>
                                        <td>
                                            <small><img class="rounded-circle" style='width:34px;height:34px;' src='{{URL::asset($employee->avatar)}}' onerror="this.src='{{URL::asset('/images/no_image.png')}}';"></small>
                                            {{$employee->last_name}}, {{$employee->first_name}}  </small>
                                        </td>
                                        <td>
                                            @if($employee->company){{$employee->company->company_name}}@endif
                                        </td>
                                        <td>@if($employee->department){{$employee->department->name}}@endif</td>
                                        <td>{{$employee->classification_info ? $employee->classification_info->name : ""}}</td>
                                        <td>@if($employee->immediate_sup_data)
                                            <small><img class="rounded-circle" style='width:34px;height:34px;' src='{{URL::asset($employee->immediate_sup_data->employee->avatar)}}' onerror="this.src='{{URL::asset('/images/no_image.png')}}';"></small>
                                            {{$employee->immediate_sup_data->name}}@endif</td>
                                        <td>{{$employee->status}} </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('employees.updateEmployeeRate')
@include('employees.uploadEmployee')
@include('employees.newEmployee')
{{-- @include('employees.capture_image') --}}


@endsection
@section('footer')
<script src="{{ asset('body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('body_css/js/inputmask.js') }}"></script>
@endsection
