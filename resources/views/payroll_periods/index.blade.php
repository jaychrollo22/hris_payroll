@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Payroll Periods</h4>
                <p class="card-description">
                    {{-- @if (checkUserPrivilege('settings_add',auth()->user()->id) == 'yes') --}}
                    <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newPayrollPeriod">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      New Payroll Period
                    </button>
                    {{-- @endif --}}
                  </p>
             
                <div class="table-responsive">
                  <table id="table-payroll" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Payroll Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Payroll Frequency</th>
                            <th>Cutoff Date</th>
                            <th>Payment Date</th>
                            <th>Total Days</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Payroll Cut-Off</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payroll_periods as $payroll_period)
                        <tr>
                            
                            <td>{{$payroll_period->payroll_name}}</td>
                            <td>{{$payroll_period->start_date}}</td>
                            <td>{{$payroll_period->end_date}}</td>
                            <td>{{$payroll_period->payroll_frequency}}</td>
                            <td>{{$payroll_period->cut_off_date}}</td>
                            <td>{{$payroll_period->payment_date}}</td>
                            <td>{{$payroll_period->total_days}}</td>
                            <td>{{$payroll_period->status}}</td>
                            <td>{{$payroll_period->notes}}</td>
                            <td>{{$payroll_period->payroll_cutoff}}</td>
                            <td>
                                {{-- @if (checkUserPrivilege('settings_edit',auth()->user()->id) == 'yes') --}}
                                <button type="button" class="btn btn-info btn-rounded btn-icon" href="#edit_payroll_period{{$payroll_period->id}}" data-toggle="modal" title='EDIT'>
                                    <i class="ti-pencil-alt"></i>
                                </button>
                                {{-- @endif --}}
                                {{-- @if (checkUserPrivilege('settings_delete',auth()->user()->id) == 'yes') --}}
                                <a href="delete-payroll-period/{{$payroll_period->id}}">
                                    <button  title='DELETE' onclick="return confirm('Are you sure you want to delete this payroll period?')" class="btn btn-rounded btn-danger btn-icon">
                                        <i class="ti-trash"></i>
                                    </button>
                                </a>
                                {{-- @endif --}}
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
@include('payroll_periods.create')
@foreach($payroll_periods as $payrollPeriod)
@include('payroll_periods.edit')
@endforeach
@endsection
