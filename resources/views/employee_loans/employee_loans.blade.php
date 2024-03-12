@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">
                  Employee Loans
                  <a href="/export-employee-loans?company={{$company}}&department={{$department}}&status={{$status}}" class="btn btn-outline-primary btn-icon-text btn-sm text-center float-right mr-2" title="Export"><i class="ti-arrow-down btn-icon-prepend"></i></a>
                </h4>
                <p class="card-description">
                    <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newEmployeeLoan">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      Add Employee Loan
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-icon-text" data-toggle="modal" data-target="#importEmployeeLoan">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      Import Employee Loans
                    </button>

                    <form method='get' onsubmit='show();' enctype="multipart/form-data">
                      <div class=row>
                        <div class="col-md-2">
                          <div class="form-group">
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search by Name or ID" value="{{$search}}"/>
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
                                <option value="Active" {{$status == 'Active' ? 'selected' : ""}}>Active</option>
                                <option value="Inactive" {{$status == 'Inactive' ? 'selected' : ""}}>Inactive</option>
                            </select>
                          </div>
                        </div>
                        <div class='col-md-2'>
                          <button type="submit" class="btn btn-primary mb-2 btn-md">Filter</button>
                          <a href="/employee-leave-type-balances" class="btn btn-warning mb-2 btn-md">Clear</a>
                        </div>
                      </div>
                    </form>

                </p>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>User ID</th>
                        <th>Employee</th>
                        <th>Company</th> 
                        <th>Department</th> 
                        <th>Date Hired</th> 
                        <th>Particular</th> 
                        <th>Loan Amount</th> 
                        <th>Action</th> 
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($employee_loans as $item)
                        <tr>
                          <td>{{ $item->user->id}}</td>
                          <td>
                            {{ $item->employee->first_name . ' ' . $item->employee->last_name}}
                          </td>
                          <td>{{ $item->employee->company ? $item->employee->company->company_name : ""}}</td>
                          <td>{{  $item->employee->department ? $item->employee->department->name : ""}}</td>
                          <td>{{ $item->employee->original_date_hired}}</td>
                          <td>{{ $item->particular}}</td>
                          <td>{{ number_format($item->payable_amount,2)}}</td>
                          <td>
                            <a href="edit-employee-loan/{{$item->id}}?search={{$search}}&company={{$company}}&department={{$department}}&status={{$status}}" class="btn btn-sm btn-primary">Edit</a>
                          </td>
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
@include('employee_loans.import_employee_loan')
@include('employee_loans.new_employee_loan')
@endsection
                
                