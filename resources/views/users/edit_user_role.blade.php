@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit User : {{$user->name}}</h4>
                        <div class="col-md-12">
                            <form method='POST' action='{{url('update-user-role/'.$user->id)}}' onsubmit='show()' enctype="multipart/form-data">
                                @csrf
                               
                                <div class="row">
                                    <div class='col-md-12 form-group'>
                                        Name
                                        <input type="text" name="name" value="{{$user->name}}" class="form-control">
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        Email
                                        <input type="email" name="email" value="{{$user->email}}" class="form-control">
                                    </div>

                                    <div class='col-md-12 form-group'>
                                        Role
                                        <select class="form-control" name="role">
                                            <option value="">Choose Role</option>
                                            <option value="Admin" {{ $user->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </div>
                                    {{-- Super User  --}}
                                    @if(auth()->user()->id == '353' || auth()->user()->id == '1' || auth()->user()->id == '5361')
                                        <div class="col-md-12 form-group">
                                            Company
                                            @php
                                                $user_allowed_companies = $user->user_allowed_company ? json_decode($user->user_allowed_company->company_ids) : [];
                                            @endphp
                                            <select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company[]' multiple>
                                                <option value="">-- Select Company --</option>
                                                    @foreach($companies as $company)
                                                    <option value="{{$company->id}}" @if (in_array($company->id,$user_allowed_companies)) selected @endif>{{$company->company_name}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            Location
                                            @php
                                                $user_allowed_locations = $user->user_allowed_location ? json_decode($user->user_allowed_location->location_ids) : [];
                                            @endphp
                                            <select data-placeholder="Select Location" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='location[]' multiple>
                                                <option value="">-- Select Location --</option>
                                                    @foreach($locations as $location)
                                                    <option value="{{$location->location}}" @if (in_array($location->location,$user_allowed_locations)) selected @endif>{{$location->location}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            Project
                                            @php
                                                $user_allowed_projects = $user->user_allowed_project ? json_decode($user->user_allowed_project->project_ids) : [];
                                            @endphp
                                            <select data-placeholder="Select Project" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='project[]' multiple>
                                                <option value="">-- Select Project --</option>
                                                    @foreach($projects as $project)
                                                    <option value="{{$project->project_id}}" @if (in_array($project->project_id,$user_allowed_projects)) selected @endif>{{$project->project_id}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        {{-- Employees --}}
                                        <div class="col-md-6 form-group">
                                            <h5>Employees</h5>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->employees_view == 'on')
                                                    <input type="checkbox" name="employees_view" id="employees_view{{$user->id}}" value="{{ $user->user_privilege->employees_view }}" checked>
                                                @else
                                                    <input type="checkbox" name="employees_view" id="employees_view{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="employees_view" id="employees_view{{$user->id}}">
                                            @endif
                                            View
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->employees_edit == 'on')
                                                    <input type="checkbox" name="employees_edit" id="employees_edit{{$user->id}}" value="{{ $user->user_privilege->employees_edit }}" checked>
                                                @else
                                                    <input type="checkbox" name="employees_edit" id="employees_edit{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="employees_edit" id="employees_edit{{$user->id}}">
                                            @endif
                                            Edit
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->employees_add == 'on')
                                                    <input type="checkbox" name="employees_add" id="employees_add{{$user->id}}" value="{{ $user->user_privilege->employees_add }}" checked>
                                                @else
                                                    <input type="checkbox" name="employees_add" id="employees_add{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="employees_add" id="employees_add{{$user->id}}">
                                            @endif
                                            New
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->employees_export == 'on')
                                                    <input type="checkbox" name="employees_export" id="employees_export{{$user->id}}" value="{{ $user->user_privilege->employees_export }}" checked>
                                                @else
                                                    <input type="checkbox" name="employees_export" id="employees_export{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="employees_export" id="employees_export{{$user->id}}">
                                            @endif 
                                            Export OTPMS
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->employees_export_hr == 'on')
                                                    <input type="checkbox" name="employees_export_hr" id="employees_export_hr{{$user->id}}" value="{{ $user->user_privilege->employees_export_hr }}" checked>
                                                @else
                                                    <input type="checkbox" name="employees_export_hr" id="employees_export_hr{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="employees_export_hr" id="employees_export_hr{{$user->id}}">
                                            @endif
                                            Export HR Details
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->employees_rate == 'on')
                                                    <input type="checkbox" name="employees_rate" id="employees_rate{{$user->id}}" value="{{ $user->user_privilege->employees_rate }}" checked>
                                                @else
                                                    <input type="checkbox" name="employees_rate" id="employees_rate{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="employees_rate" id="employees_rate{{$user->id}}">
                                            @endif
                                            Rate
                                            <br>
                                            <br>
                                        </div>
                                        {{-- Reports --}}
                                        <div class="col-md-6 form-group">
                                            <h5>Reports</h5>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->reports_leave == 'on')
                                                    <input type="checkbox" name="reports_leave" id="reports_leave{{$user->id}}" value="{{ $user->user_privilege->reports_leave }}" checked>
                                                @else
                                                    <input type="checkbox" name="reports_leave" id="reports_leave{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="reports_leave" id="reports_leave{{$user->id}}">
                                            @endif
                                            Leave
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->reports_overtime == 'on')
                                                    <input type="checkbox" name="reports_overtime" id="reports_overtime{{$user->id}}" value="{{ $user->user_privilege->reports_overtime }}" checked>
                                                @else
                                                    <input type="checkbox" name="reports_overtime" id="reports_overtime{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="reports_overtime" id="reports_overtime{{$user->id}}">
                                            @endif
                                            Overtime
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->reports_wfh == 'on')
                                                    <input type="checkbox" name="reports_wfh" id="reports_wfh{{$user->id}}" value="{{ $user->user_privilege->reports_wfh }}" checked>
                                                @else
                                                    <input type="checkbox" name="reports_wfh" id="reports_wfh{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="reports_wfh" id="reports_wfh{{$user->id}}">
                                            @endif
                                            Work From Home
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->reports_ob == 'on')
                                                    <input type="checkbox" name="reports_ob" id="reports_ob{{$user->id}}" value="{{ $user->user_privilege->reports_ob }}" checked>
                                                @else
                                                    <input type="checkbox" name="reports_ob" id="reports_ob{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="reports_ob" id="reports_ob{{$user->id}}">
                                            @endif
                                            Official Business
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->reports_dtr == 'on')
                                                    <input type="checkbox" name="reports_dtr" id="reports_dtr{{$user->id}}" value="{{ $user->user_privilege->reports_dtr }}" checked>
                                                @else
                                                    <input type="checkbox" name="reports_dtr" id="reports_dtr{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="reports_dtr" id="reports_dtr{{$user->id}}">
                                            @endif
                                            Daily Time Record
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->reports_ppr == 'on')
                                                    <input type="checkbox" name="reports_ppr" id="reports_ppr{{$user->id}}" value="{{ $user->user_privilege->reports_ppr }}" checked>
                                                @else
                                                    <input type="checkbox" name="reports_ppr" id="reports_ppr{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="reports_dtr" id="reports_ppr{{$user->id}}">
                                            @endif
                                            Performance Plan Report
                                            <br>
                                            <br>
                                        </div>
                                        {{-- Biometrics --}}
                                        <div class="col-md-6 form-group">
                                            <h5>Biometrics</h5>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->biometrics_per_employee == 'on')
                                                    <input type="checkbox" name="biometrics_per_employee" id="biometrics_per_employee{{$user->id}}" value="{{ $user->user_privilege->biometrics_per_employee }}" checked>
                                                @else
                                                    <input type="checkbox" name="biometrics_per_employee" id="biometrics_per_employee{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="biometrics_per_employee" id="biometrics_per_employee{{$user->id}}">
                                            @endif
                                            Per Employee
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->biometrics_per_location == 'on')
                                                    <input type="checkbox" name="biometrics_per_location" id="biometrics_per_location{{$user->id}}" value="{{ $user->user_privilege->biometrics_per_location }}" checked>
                                                @else
                                                    <input type="checkbox" name="biometrics_per_location" id="biometrics_per_location{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="biometrics_per_location" id="biometrics_per_location{{$user->id}}">
                                            @endif
                                            Per Location (ZK TECO)
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->biometrics_per_location_hik == 'on')
                                                    <input type="checkbox" name="biometrics_per_location_hik" id="biometrics_per_location_hik{{$user->id}}" value="{{ $user->user_privilege->biometrics_per_location_hik }}" checked>
                                                @else
                                                    <input type="checkbox" name="biometrics_per_location_hik" id="biometrics_per_location_hik{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="biometrics_per_location_hik" id="biometrics_per_location_hik{{$user->id}}">
                                            @endif
                                            Per Location (HIK VISION)
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->biometrics_per_company == 'on')
                                                    <input type="checkbox" name="biometrics_per_company" id="biometrics_per_company{{$user->id}}" value="{{ $user->user_privilege->biometrics_per_company }}" checked>
                                                @else
                                                    <input type="checkbox" name="biometrics_per_company" id="biometrics_per_company{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="biometrics_per_company" id="biometrics_per_company{{$user->id}}">
                                            @endif
                                            Per Company
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->biometrics_per_seabased == 'on')
                                                    <input type="checkbox" name="biometrics_per_seabased" id="biometrics_per_seabased{{$user->id}}" value="{{ $user->user_privilege->biometrics_per_seabased }}" checked>
                                                @else
                                                    <input type="checkbox" name="biometrics_per_seabased" id="biometrics_per_seabased{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="biometrics_per_seabased" id="biometrics_per_seabased{{$user->id}}">
                                            @endif
                                            Per Seabased
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->biometrics_per_hik_vision == 'on')
                                                    <input type="checkbox" name="biometrics_per_hik_vision" id="biometrics_per_hik_vision{{$user->id}}" value="{{ $user->user_privilege->biometrics_per_hik_vision }}" checked>
                                                @else
                                                    <input type="checkbox" name="biometrics_per_hik_vision" id="biometrics_per_hik_vision{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="biometrics_per_hik_vision" id="biometrics_per_hik_vision{{$user->id}}">
                                            @endif
                                            Per HIK Vision
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->biometrics_sync == 'on')
                                                    <input type="checkbox" name="biometrics_sync" id="biometrics_sync{{$user->id}}" value="{{ $user->user_privilege->biometrics_sync }}" checked>
                                                @else
                                                    <input type="checkbox" name="biometrics_sync" id="biometrics_sync{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="biometrics_sync" id="biometrics_sync{{$user->id}}">
                                            @endif
                                            Sync Biometric
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->timekeeping_dashboard == 'on')
                                                    <input type="checkbox" name="timekeeping_dashboard" id="timekeeping_dashboard{{$user->id}}" value="{{ $user->user_privilege->timekeeping_dashboard }}" checked>
                                                @else
                                                    <input type="checkbox" name="timekeeping_dashboard" id="timekeeping_dashboard{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="timekeeping_dashboard" id="timekeeping_dashboard{{$user->id}}">
                                            @endif
                                            Timekeeping Dashboard
                                            <br>
                                            <br>
                                        </div>
                                        {{-- Settings --}}
                                        <div class="col-md-6 form-group">
                                            <h5>Settings</h5>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->settings_view == 'on')
                                                    <input type="checkbox" name="settings_view" id="settings_view{{$user->id}}" value="{{ $user->user_privilege->settings_view }}" checked>
                                                @else
                                                    <input type="checkbox" name="settings_view" id="settings_view{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="settings_view" id="settings_view{{$user->id}}">
                                            @endif
                                            View
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->settings_add == 'on')
                                                    <input type="checkbox" name="settings_add" id="settings_add{{$user->id}}" value="{{ $user->user_privilege->settings_add }}" checked>
                                                @else
                                                    <input type="checkbox" name="settings_add" id="settings_add{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="settings_add" id="settings_add{{$user->id}}">
                                            @endif
                                            Add
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->settings_edit == 'on')
                                                    <input type="checkbox" name="settings_edit" id="settings_edit{{$user->id}}" value="{{ $user->user_privilege->settings_edit }}" checked>
                                                @else
                                                    <input type="checkbox" name="settings_edit" id="settings_edit{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="settings_edit" id="settings_edit{{$user->id}}">
                                            @endif
                                            Edit
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->settings_delete == 'on')
                                                    <input type="checkbox" name="settings_delete" id="settings_delete{{$user->id}}" value="{{ $user->user_privilege->settings_delete }}" checked>
                                                @else
                                                    <input type="checkbox" name="settings_delete" id="settings_delete{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="settings_delete" id="settings_delete{{$user->id}}">
                                            @endif
                                            Delete
                                            <br>
                                            <br>
                                        </div>
                                        {{-- Masterfiles --}}
                                        <div class="col-md-6 form-group">
                                            <h5>Masterfiles</h5>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->masterfiles_early_cutoffs == 'on')
                                                    <input type="checkbox" name="masterfiles_early_cutoffs" id="masterfiles_early_cutoffs{{$user->id}}" value="{{ $user->user_privilege->masterfiles_early_cutoffs }}" checked>
                                                @else
                                                    <input type="checkbox" name="masterfiles_early_cutoffs" id="masterfiles_early_cutoffs{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="masterfiles_early_cutoffs" id="masterfiles_early_cutoffs{{$user->id}}">
                                            @endif
                                            Early Cutoffs
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->masterfiles_cost_centers == 'on')
                                                    <input type="checkbox" name="masterfiles_cost_centers" id="masterfiles_cost_centers{{$user->id}}" value="{{ $user->user_privilege->masterfiles_cost_centers }}" checked>
                                                @else
                                                    <input type="checkbox" name="masterfiles_cost_centers" id="masterfiles_cost_centers{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="masterfiles_cost_centers" id="masterfiles_cost_centers{{$user->id}}">
                                            @endif
                                            Cost Centers
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->masterfiles_companies == 'on')
                                                    <input type="checkbox" name="masterfiles_companies" id="masterfiles_companies{{$user->id}}" value="{{ $user->user_privilege->masterfiles_companies }}" checked>
                                                @else
                                                    <input type="checkbox" name="masterfiles_companies" id="masterfiles_companies{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="masterfiles_companies" id="masterfiles_companies{{$user->id}}">
                                            @endif
                                            Companies
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->masterfiles_departments == 'on')
                                                    <input type="checkbox" name="masterfiles_departments" id="masterfiles_departments{{$user->id}}" value="{{ $user->user_privilege->masterfiles_departments }}" checked>
                                                @else
                                                    <input type="checkbox" name="masterfiles_departments" id="masterfiles_departments{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="masterfiles_departments" id="masterfiles_departments{{$user->id}}">
                                            @endif
                                            Departments
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->masterfiles_locations == 'on')
                                                    <input type="checkbox" name="masterfiles_locations" id="masterfiles_locations{{$user->id}}" value="{{ $user->user_privilege->masterfiles_locations }}" checked>
                                                @else
                                                    <input type="checkbox" name="masterfiles_locations" id="masterfiles_locations{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="masterfiles_locations" id="masterfiles_locations{{$user->id}}">
                                            @endif
                                            Locations
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->masterfiles_projects == 'on')
                                                    <input type="checkbox" name="masterfiles_projects" id="masterfiles_projects{{$user->id}}" value="{{ $user->user_privilege->masterfiles_projects }}" checked>
                                                @else
                                                    <input type="checkbox" name="masterfiles_projects" id="masterfiles_projects{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="masterfiles_projects" id="masterfiles_projects{{$user->id}}">
                                            @endif
                                            Projects
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->masterfiles_vessels == 'on')
                                                    <input type="checkbox" name="masterfiles_vessels" id="masterfiles_vessels{{$user->id}}" value="{{ $user->user_privilege->masterfiles_projects }}" checked>
                                                @else
                                                    <input type="checkbox" name="masterfiles_vessels" id="masterfiles_vessels{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="masterfiles_vessels" id="masterfiles_vessels{{$user->id}}">
                                            @endif
                                            Vessels
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->masterfiles_loan_types == 'on')
                                                    <input type="checkbox" name="masterfiles_loan_types" id="masterfiles_loan_types{{$user->id}}" value="{{ $user->user_privilege->masterfiles_loan_types }}" checked>
                                                @else
                                                    <input type="checkbox" name="masterfiles_loan_types" id="masterfiles_loan_types{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="masterfiles_loan_types" id="masterfiles_loan_types{{$user->id}}">
                                            @endif
                                            Loan Types
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->masterfiles_employee_leave_credits == 'on')
                                                    <input type="checkbox" name="masterfiles_employee_leave_credits" id="masterfiles_employee_leave_credits{{$user->id}}" value="{{ $user->user_privilege->masterfiles_employee_leave_credits }}" checked>
                                                @else
                                                    <input type="checkbox" name="masterfiles_employee_leave_credits" id="masterfiles_employee_leave_credits{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="masterfiles_employee_leave_credits" id="masterfiles_employee_leave_credits{{$user->id}}">
                                            @endif
                                            Employee Leave Credits / Employee Leave Balances
                                            {{-- <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->masterfiles_employee_leave_earned == 'on')
                                                    <input type="checkbox" name="masterfiles_employee_leave_earned" id="masterfiles_employee_leave_earned{{$user->id}}" value="{{ $user->user_privilege->masterfiles_employee_leave_earned }}" checked>
                                                @else
                                                    <input type="checkbox" name="masterfiles_employee_leave_earned" id="masterfiles_employee_leave_earned{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="masterfiles_employee_leave_earned" id="masterfiles_employee_leave_earned{{$user->id}}">
                                            @endif --}}
                                            {{-- Employee Earned Leaves --}}
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->masterfiles_employee_allowances == 'on')
                                                    <input type="checkbox" name="masterfiles_employee_allowances" id="masterfiles_employee_allowances{{$user->id}}" value="{{ $user->user_privilege->masterfiles_employee_allowances }}" checked>
                                                @else
                                                    <input type="checkbox" name="masterfiles_employee_allowances" id="masterfiles_employee_allowances{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="masterfiles_employee_allowances" id="masterfiles_employee_allowances{{$user->id}}">
                                            @endif
                                            Employee Allowances
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->masterfiles_employee_loans == 'on')
                                                    <input type="checkbox" name="masterfiles_employee_loans" id="masterfiles_employee_loans{{$user->id}}" value="{{ $user->user_privilege->masterfiles_employee_loans }}" checked>
                                                @else
                                                    <input type="checkbox" name="masterfiles_employee_loans" id="masterfiles_employee_loans{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="masterfiles_employee_loans" id="masterfiles_employee_loans{{$user->id}}">
                                            @endif
                                            Employee Loans
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->masterfiles_performance_plan_periods == 'on')
                                                    <input type="checkbox" name="masterfiles_performance_plan_periods" id="masterfiles_performance_plan_periods{{$user->id}}" value="{{ $user->user_privilege->masterfiles_performance_plan_periods }}" checked>
                                                @else
                                                    <input type="checkbox" name="masterfiles_performance_plan_periods" id="masterfiles_performance_plan_periods{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="masterfiles_performance_plan_periods" id="masterfiles_performance_plan_periods{{$user->id}}">
                                            @endif
                                            Performance Plan Periods
                                            <br>
                                            <br>
                                        </div>
                                    @endif
                                    
                                    {{-- Timekeeper Role --}}
                                    @if(auth()->user()->id == '351')
                                        <div class="col-md-12 form-group">
                                            Company
                                            @php
                                                $user_allowed_companies = $user->user_allowed_company ? json_decode($user->user_allowed_company->company_ids) : [];
                                            @endphp
                                            <select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company[]' multiple>
                                                <option value="">-- Select Company --</option>
                                                    @foreach($companies as $company)
                                                    <option value="{{$company->id}}" @if (in_array($company->id,$user_allowed_companies)) selected @endif>{{$company->company_name}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            Location
                                            @php
                                                $user_allowed_locations = $user->user_allowed_location ? json_decode($user->user_allowed_location->location_ids) : [];
                                            @endphp
                                            <select data-placeholder="Select Location" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='location[]' multiple>
                                                <option value="">-- Select Location --</option>
                                                    @foreach($locations as $location)
                                                    <option value="{{$location->location}}" @if (in_array($location->location,$user_allowed_locations)) selected @endif>{{$location->location}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            Project
                                            @php
                                                $user_allowed_projects = $user->user_allowed_project ? json_decode($user->user_allowed_project->project_ids) : [];
                                            @endphp
                                            <select data-placeholder="Select Project" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='project[]' multiple>
                                                <option value="">-- Select Project --</option>
                                                    @foreach($projects as $project)
                                                    <option value="{{$project->project_id}}" @if (in_array($project->project_id,$user_allowed_projects)) selected @endif>{{$project->project_id}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <h5>Biometrics</h5>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->biometrics_per_employee == 'on')
                                                    <input type="checkbox" name="biometrics_per_employee" id="biometrics_per_employee{{$user->id}}" value="{{ $user->user_privilege->biometrics_per_employee }}" checked>
                                                @else
                                                    <input type="checkbox" name="biometrics_per_employee" id="biometrics_per_employee{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="biometrics_per_employee" id="biometrics_per_employee{{$user->id}}">
                                            @endif
                                            Per Employee
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->biometrics_per_location == 'on')
                                                    <input type="checkbox" name="biometrics_per_location" id="biometrics_per_location{{$user->id}}" value="{{ $user->user_privilege->biometrics_per_location }}" checked>
                                                @else
                                                    <input type="checkbox" name="biometrics_per_location" id="biometrics_per_location{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="biometrics_per_location" id="biometrics_per_location{{$user->id}}">
                                            @endif
                                            Per Location (ZK TECO)
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->biometrics_per_location_hik == 'on')
                                                    <input type="checkbox" name="biometrics_per_location_hik" id="biometrics_per_location_hik{{$user->id}}" value="{{ $user->user_privilege->biometrics_per_location_hik }}" checked>
                                                @else
                                                    <input type="checkbox" name="biometrics_per_location_hik" id="biometrics_per_location_hik{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="biometrics_per_location_hik" id="biometrics_per_location_hik{{$user->id}}">
                                            @endif
                                            Per Location (HIK VISION)
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->biometrics_per_company == 'on')
                                                    <input type="checkbox" name="biometrics_per_company" id="biometrics_per_company{{$user->id}}" value="{{ $user->user_privilege->biometrics_per_company }}" checked>
                                                @else
                                                    <input type="checkbox" name="biometrics_per_company" id="biometrics_per_company{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="biometrics_per_company" id="biometrics_per_company{{$user->id}}">
                                            @endif
                                            Per Company
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->biometrics_per_seabased == 'on')
                                                    <input type="checkbox" name="biometrics_per_seabased" id="biometrics_per_seabased{{$user->id}}" value="{{ $user->user_privilege->biometrics_per_seabased }}" checked>
                                                @else
                                                    <input type="checkbox" name="biometrics_per_seabased" id="biometrics_per_seabased{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="biometrics_per_seabased" id="biometrics_per_seabased{{$user->id}}">
                                            @endif
                                            Per Seabased
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->biometrics_per_hik_vision == 'on')
                                                    <input type="checkbox" name="biometrics_per_hik_vision" id="biometrics_per_hik_vision{{$user->id}}" value="{{ $user->user_privilege->biometrics_per_hik_vision }}" checked>
                                                @else
                                                    <input type="checkbox" name="biometrics_per_hik_vision" id="biometrics_per_hik_vision{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="biometrics_per_hik_vision" id="biometrics_per_hik_vision{{$user->id}}">
                                            @endif
                                            Per HIK Vision
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->biometrics_sync == 'on')
                                                    <input type="checkbox" name="biometrics_sync" id="biometrics_sync{{$user->id}}" value="{{ $user->user_privilege->biometrics_sync }}" checked>
                                                @else
                                                    <input type="checkbox" name="biometrics_sync" id="biometrics_sync{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="biometrics_sync" id="biometrics_sync{{$user->id}}">
                                            @endif
                                            Sync Biometric
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->timekeeping_dashboard == 'on')
                                                    <input type="checkbox" name="timekeeping_dashboard" id="timekeeping_dashboard{{$user->id}}" value="{{ $user->user_privilege->timekeeping_dashboard }}" checked>
                                                @else
                                                    <input type="checkbox" name="timekeeping_dashboard" id="timekeeping_dashboard{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="timekeeping_dashboard" id="timekeeping_dashboard{{$user->id}}">
                                            @endif
                                            Timekeeping Dashboard
                                            <br>
                                            <br>
                                        </div>

                                        {{-- Reports --}}
                                        <div class="col-md-6 form-group">
                                            <h5>Reports</h5>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->reports_leave == 'on')
                                                    <input type="checkbox" name="reports_leave" id="reports_leave{{$user->id}}" value="{{ $user->user_privilege->reports_leave }}" checked>
                                                @else
                                                    <input type="checkbox" name="reports_leave" id="reports_leave{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="reports_leave" id="reports_leave{{$user->id}}">
                                            @endif
                                            Leave
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->reports_overtime == 'on')
                                                    <input type="checkbox" name="reports_overtime" id="reports_overtime{{$user->id}}" value="{{ $user->user_privilege->reports_overtime }}" checked>
                                                @else
                                                    <input type="checkbox" name="reports_overtime" id="reports_overtime{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="reports_overtime" id="reports_overtime{{$user->id}}">
                                            @endif
                                            Overtime
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->reports_wfh == 'on')
                                                    <input type="checkbox" name="reports_wfh" id="reports_wfh{{$user->id}}" value="{{ $user->user_privilege->reports_wfh }}" checked>
                                                @else
                                                    <input type="checkbox" name="reports_wfh" id="reports_wfh{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="reports_wfh" id="reports_wfh{{$user->id}}">
                                            @endif
                                            Work From Home
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->reports_ob == 'on')
                                                    <input type="checkbox" name="reports_ob" id="reports_ob{{$user->id}}" value="{{ $user->user_privilege->reports_ob }}" checked>
                                                @else
                                                    <input type="checkbox" name="reports_ob" id="reports_ob{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="reports_ob" id="reports_ob{{$user->id}}">
                                            @endif
                                            Official Business
                                            <br>
                                            <br>
                                            @if($user->user_privilege)
                                                @if($user->user_privilege->reports_dtr == 'on')
                                                    <input type="checkbox" name="reports_dtr" id="reports_dtr{{$user->id}}" value="{{ $user->user_privilege->reports_dtr }}" checked>
                                                @else
                                                    <input type="checkbox" name="reports_dtr" id="reports_dtr{{$user->id}}">
                                                @endif
                                            @else
                                                <input type="checkbox" name="reports_dtr" id="reports_dtr{{$user->id}}">
                                            @endif
                                            Daily Time Record
                                            <br>
                                            <br>
                                        </div>
                                    @endif
                                </div>
                            
                                <a href='/users' type="button" class="btn btn-secondary">Close</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                         
                            </form>
                        </div>

                    </div>
                </div>
            </div>
           
        </div>
    </div>
</div>
@endsection
@section('footer')
<script src="{{ asset('body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('body_css/vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('body_css/js/inputmask.js') }}"></script>
@endsection
