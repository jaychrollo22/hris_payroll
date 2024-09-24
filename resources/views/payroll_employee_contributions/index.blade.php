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
                  </p>
             
                <div class="table-responsive">
                  <table id="table-payroll" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>User ID</th>
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
@include('payroll_employee_contributions.create')
@foreach($contributions as $contribution)
@include('payroll_employee_contributions.edit')
@endforeach
@endsection
