@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Payroll Employee Contributions</h4>
                  <p class="card-description">
                    {{-- @if (checkUserPrivilege('settings_add',auth()->user()->id) == 'yes') --}}
                      <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newPayrollEmployeeContribution">
                        <i class="ti-plus btn-icon-prepend"></i>                                                    
                        New
                      </button>
                    {{-- @endif --}}

                      <button type="button" class="btn btn-outline-primary btn-icon-text" data-toggle="modal" data-target="#importPayrollEmployeeContribution">
                        <i class="ti-plus btn-icon-prepend"></i>                                                    
                        Import
                      </button>

                  </p>
                
                  <h4 class="card-title">Filter</h4>
                  <p class="card-description">
                  <form method='get' onsubmit='show();' enctype="multipart/form-data">
                    <div class=row>
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
                            <th>Employee</th>
                            <th>Company</th>
                            <th>SSS REG EE</th>
                            <th>SSS MPF EE</th>
                            <th>PHIC EE</th>
                            <th>HDMF EE</th>
                            <th>SSS REG ER</th>
                            <th>SSS MPF ER</th>
                            <th>SSS EC</th>
                            <th>PHIC ER</th>
                            <th>HDMF ER</th>
                            <th>Payment Schedule</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach ($contributions as $contribution)
                      <tr>
                          <td>{{ $contribution->user_id }}</td>
                          <td>{{ $contribution->employee->first_name . ' ' . $contribution->employee->last_name }}</td>
                          <td>{{ $contribution->employee->company->company_name }}</td>
                          <td>{{ number_format($contribution->sss_reg_ee, 2) }}</td>
                          <td>{{ number_format($contribution->sss_mpf_ee, 2) }}</td>
                          <td>{{ number_format($contribution->phic_ee, 2) }}</td>
                          <td>{{ number_format($contribution->hdmf_ee, 2) }}</td>
                          <td>{{ number_format($contribution->sss_reg_er, 2) }}</td>
                          <td>{{ number_format($contribution->sss_mpf_er, 2) }}</td>
                          <td>{{ number_format($contribution->sss_ec, 2) }}</td>
                          <td>{{ number_format($contribution->phic_er, 2) }}</td>
                          <td>{{ number_format($contribution->hdmf_er, 2) }}</td>
                          <td>{{ $contribution->payment_schedule }}</td>
                          <td>
                            <button type="button" class="btn btn-info btn-rounded btn-icon" href="#edit_payroll_employee_contribution{{$contribution->id}}" data-toggle="modal" title='EDIT'>
                                <i class="ti-pencil-alt"></i>
                            </button>
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
@include('payroll_employee_contributions.import')
@include('payroll_employee_contributions.create')
@foreach($contributions as $contribution)
@include('payroll_employee_contributions.edit')
@endforeach
@endsection
