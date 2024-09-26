@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <p class="card-description">
                    <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newPayrollSalaryAdjustment">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      Add new Salary Adjustment 
                    </button>

                    <button type="button" class="btn btn-outline-primary btn-icon-text" data-toggle="modal" data-target="#importPayrollSalaryAdjustment">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      Import Employee Allowance
                    </button>
                </p>

                <h4 class="card-title">Salary Adjustment <a href="/payroll-salary-adjusments-export?company={{$company}}&status={{$status}}" title="Export" class="btn btn-outline-primary btn-icon-text btn-sm text-center"><i class="ti-arrow-down btn-icon-prepend"></i></a></h4>
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
                        <th>Effectivity Date</th> 
                        <th>Amount</th> 
                        <th>Type</th>
                        <th>Status</th>
                        <th>Reason</th> 
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($salary_adjustments as $salary_adjustment)
                        <tr class='cursor-pointer'>
                            <td>{{ $salary_adjustment->employee ? $salary_adjustment->employee->user_id : "" }}</td>
                            <td>
                              {{ $salary_adjustment->employee ? $salary_adjustment->employee->last_name . ', ' . $salary_adjustment->employee->first_name . ' ' . $salary_adjustment->employee->middle_name : "" }}
                              <br>
											        <small>{{$salary_adjustment->employee ? $salary_adjustment->employee->company->company_name : ""}}</small>
                            </td>
                            <td>{{$salary_adjustment->effectivity_date}}</td>
                            <td>{{$salary_adjustment->amount}}</td>
                            <td>{{$salary_adjustment->type}}</td>
                            <td>{{$salary_adjustment->status}}</td>
                            <td>{{$salary_adjustment->reason}}</td>
                            <td>
                              @if (checkUserPrivilege('settings_edit',auth()->user()->id) == 'yes')
                              <button type="button" class="btn btn-info btn-rounded btn-icon" href="#editSalaryAdjustment{{$salary_adjustment->id}}" data-toggle="modal" title='EDIT'>
                                  <i class="ti-pencil-alt"></i>
                              </button>
                              @endif
                              @if (checkUserPrivilege('settings_delete',auth()->user()->id) == 'yes')
                              <a href="delete-payroll-salary-adjustment/{{$salary_adjustment->id}}">
                                  <button  title='DELETE' onclick="return confirm('Are you sure you want to delete this Salary Adjustment?')" class="btn btn-rounded btn-danger btn-icon">
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
          @include('salary_adjustments.form_add')
          @foreach($salary_adjustments as $salary_adjustment)
          @include('salary_adjustments.form_edit')
          @endforeach
          @include('salary_adjustments.import')
        </div>
    </div>
</div>
@endsection
