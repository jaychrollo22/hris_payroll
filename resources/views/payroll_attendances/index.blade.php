@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                  <p class="card-description">
                    {{-- @if (checkUserPrivilege('settings_add',auth()->user()->id) == 'yes') --}}
                      <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#generate_payroll_attendance">
                        <i class="ti-plus btn-icon-prepend"></i>                                                    
                        Generate Payroll Attendance
                      </button>
                    {{-- @endif --}}

                      <button type="button" class="btn btn-outline-primary btn-icon-text" data-toggle="modal" data-target="#importPayrollAttendance">
                        <i class="ti-plus btn-icon-prepend"></i>                                                    
                        Import Payroll Attendance
                      </button>

                      <a type="button" class="btn btn-outline-danger btn-icon-text" href="{{ url('/payroll-overtime-adjustments') }}" target="_blank">
                        <i class="ti-plus btn-icon-prepend"></i>                                                    
                        Overtime Adjustments
                      </a>
                  </p>
                  
                  <h4 class="card-title">Payroll Attendance <a href="/payroll-attendances-export?company={{$company}}&payroll_period={{$payroll_period}}" title="Export" class="btn btn-outline-primary btn-icon-text btn-sm text-center"><i class="ti-arrow-down btn-icon-prepend"></i></a></h4>
                  <h4 class="card-title">Filter</h4>
                  <p class="card-description">
                  <form method='get' onsubmit='show();' enctype="multipart/form-data">
                    <div class=row>
                      <div class='col-md-3'>
                        <div class="form-group">
                          <select data-placeholder="Select Payroll Period" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='payroll_period' required>
                            <option value="">-- Select Payroll Period --</option>
                            @foreach($payroll_periods as $period)
                            <option value="{{$period->id}}" @if ($period->id == $payroll_period) selected @endif>{{$period->payroll_name}} - ({{$period->start_date . '-' . $period->end_date}})</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class='col-md-3'>
                        <div class="form-group">
                          <select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company' required>
                            <option value="">-- Select Company --</option>
                            @foreach($companies as $comp)
                            <option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_name}} - {{$comp->company_code}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>

                      <div class='col-md-2'>
                        <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Filter</button>
                      </div>
                    </div>
                    
                  </form>
             
                <div class="table-responsive">
                  <table id="table-payroll" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Full Name</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Location</th>
                            <th>Basic Pay</th>
                            <th>Daily Rate</th>
                            <th>Hourly Rate</th>
                            <th>Days Worked</th>
                            <th>Days Worked Amount</th>
                            <th>Sick Leave Days</th>
                            <th>Sick Leave Amount</th>
                            <th>Vacation Leave Days</th>
                            <th>Vacation Leave Amount</th>
                            <th>Absences Days</th>
                            <th>Absences Amount</th>
                            <th>Lates Hours</th>
                            <th>Lates Amount</th>
                            <th>Undertime Hours</th>
                            <th>Undertime Amount</th>
                            <th>Regular OT Hours</th>
                            <th>Regular OT Amount</th>
                            <th>Overtime Adjustment</th>
                            <th>Total Overtime Pay</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payroll_attendances as $payroll_attendance)
                        <tr>
                            <td>{{ $payroll_attendance->user_id }}</td>
                            <td>{{ $payroll_attendance->full_name }}</td>
                            <td>{{ $payroll_attendance->company }}</td>
                            <td>{{ $payroll_attendance->department }}</td>
                            <td>{{ $payroll_attendance->location }}</td>
                            <td>{{ $payroll_attendance->basic_pay }}</td>
                            <td>{{ $payroll_attendance->daily_rate }}</td>
                            <td>{{ $payroll_attendance->hourly_rate }}</td>
                            <td>{{ $payroll_attendance->no_of_days_worked }}</td>
                            <td>{{ $payroll_attendance->days_worked_amount }}</td>
                            <td>{{ $payroll_attendance->sl_with_pay_days }}</td>
                            <td>{{ $payroll_attendance->sl_with_pay_amount }}</td>
                            <td>{{ $payroll_attendance->vl_with_pay_days }}</td>
                            <td>{{ $payroll_attendance->vl_with_pay_amount }}</td>
                            <td>{{ $payroll_attendance->absences_days }}</td>
                            <td>{{ $payroll_attendance->absences_amount }}</td>
                            <td>{{ $payroll_attendance->lates_hours }}</td>
                            <td>{{ $payroll_attendance->lates_amount }}</td>
                            <td>{{ $payroll_attendance->undertime_hours }}</td>
                            <td>{{ $payroll_attendance->undertime_amount }}</td>
                            <td>{{ $payroll_attendance->reg_ot_hours }}</td>
                            <td>{{ $payroll_attendance->reg_ot_amount }}</td>
                            <td>{{ $payroll_attendance->overtime_adjustment }}</td>
                            <td>{{ $payroll_attendance->total_overtime_pay }}</td>
                            <td>{{ $payroll_attendance->status }}</td>
                            <td>{{ $payroll_attendance->remarks }}</td>
                            
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
@include('payroll_attendances.generate_payroll_attendance') 

{{-- @include('payroll_employee_contributions.import')
@include('payroll_employee_contributions.create')
@foreach($contributions as $contribution)
@include('payroll_employee_contributions.edit')
@endforeach --}}
@endsection
