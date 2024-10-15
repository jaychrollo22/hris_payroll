@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <p class="card-description">
                    <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newPayrollOvertimeAdjustment">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      Add new Overtime Adjustment 
                    </button>

                    <button type="button" class="btn btn-outline-primary btn-icon-text" data-toggle="modal" data-target="#importPayrollOvertimeAdjustment">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      Import Overtime Adjustment
                    </button>
                </p>

                <h4 class="card-title">Overtime Adjustment <a href="/payroll-overtime-adjustments-export?company={{$company}}&payroll_period={{$payroll_period}}&status={{$status}}" title="Export" class="btn btn-outline-primary btn-icon-text btn-sm text-center"><i class="ti-arrow-down btn-icon-prepend"></i></a></h4>
                <h4 class="card-title">Filter</h4>
                <p class="card-description">
                <form method='get' onsubmit='show();' enctype="multipart/form-data">
                  <div class=row>
                    <div class='col-md-4'>
                      <div class="form-group">
                        <label class="text-right">Company</label>
                        <select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company' required>
                          <option value="">-- Select Company --</option>
                          @foreach($companies as $comp)
                          <option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_name}} - {{$comp->company_code}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class='col-md-2 mr-2'>
                      <div class="form-group">
                        <label class="text-right">Payroll Period</label>
                        <select data-placeholder="Select Payroll Period" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='payroll_period' required>
                          <option value="">-- Select Payroll Period --</option>
                          @foreach($payroll_periods as $payroll_period_item)
                          <option value="{{$payroll_period_item->id}}" @if ($payroll_period_item->id == $payroll_period) selected @endif>{{$payroll_period_item->payroll_name}} ({{$payroll_period_item->start_date .'-'. $payroll_period_item->end_date}})</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class='col-md-2 mr-2'>
                      <div class="form-group">
                        <label class="text-right">Status</label>
                        <select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status' required>
                          <option value="">-- Select Status --</option>
                          <option value="Active" @if ('Active' == $status) selected @endif>Active</option>
                          <option value="Inactive" @if ('Inactive' == $status) selected @endif>Inactive</option>
                        </select>
                      </div>
                    </div>
                    <div class='col-md-2'>
                      <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Generate</button>
                    </div>
                  </div>
                  
                </form>
                </p>

                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>User ID</th>
                        <th>Name</th> 
                        <th>Payroll Period</th>
                        <th>Amount</th> 
                        <th>Type</th>
                        <th>Status</th>
                        <th>Reason</th> 
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($overtime_adjustments as $overtime_adjustment)
                        <tr class='cursor-pointer'>
                            <td>{{ $overtime_adjustment->employee ? $overtime_adjustment->employee->user_id : "" }}</td>
                            <td>
                              {{ $overtime_adjustment->employee ? $overtime_adjustment->employee->last_name . ', ' . $overtime_adjustment->employee->first_name . ' ' . $overtime_adjustment->employee->middle_name : "" }}
                              <br>
											        <small>{{$overtime_adjustment->employee ? $overtime_adjustment->employee->company->company_name : ""}}</small>
                            </td>
                            <td>{{$overtime_adjustment->payrollPeriod ? $overtime_adjustment->payrollPeriod->payroll_name. " (".$overtime_adjustment->payrollPeriod->start_date .'-'. $overtime_adjustment->payrollPeriod->end_date.")" : ""}}</td>
                            <td>{{$overtime_adjustment->amount}}</td>
                            <td>{{$overtime_adjustment->type}}</td>
                            <td>{{$overtime_adjustment->status}}</td>
                            <td>{{$overtime_adjustment->reason}}</td>
                            <td>
                              @if (checkUserPrivilege('settings_edit',auth()->user()->id) == 'yes')
                              <button type="button" class="btn btn-info btn-rounded btn-icon" href="#editOvertimeAdjustment{{$overtime_adjustment->id}}" data-toggle="modal" title='EDIT'>
                                  <i class="ti-pencil-alt"></i>
                              </button>
                              @endif
                              @if (checkUserPrivilege('settings_delete',auth()->user()->id) == 'yes')
                              <a href="delete-payroll-overtime-adjustment/{{$overtime_adjustment->id}}">
                                  <button  title='DELETE' onclick="return confirm('Are you sure you want to delete this Overtime Adjustment?')" class="btn btn-rounded btn-danger btn-icon">
                                      <i class="ti-trash"></i>
                                  </button>
                              </a>
                              @endif
                            </td>
                          </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          @include('overtime_adjustments.form_add')
          @foreach($overtime_adjustments as $overtime_adjustment)
          @include('overtime_adjustments.form_edit')
          @endforeach
          @include('overtime_adjustments.import')
        </div>
    </div>
</div>
@endsection
