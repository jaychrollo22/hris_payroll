@extends('layouts.header')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
            @if (count($errors))
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show " role="alert">
                        <span class="alert-inner--icon"><i class="ni ni-fat-remove"></i></span>
                        <span class="alert-inner--text"><strong>Error!</strong> {{ $error }}</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endforeach
            @endif
        @include('links')
        <div id="payroll" class="tab-pane  active">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">13 Month  <button class='btn btn-info' onclick="exportTableToExcel('monthly_pay','{{date('Y-m-d')}}')">Export</button></h4>
               
                <div class="table-responsive">
                  <table class="table table-hover table-bordered " id='monthly_pay'>
                    <thead>
                        <tr>
                            <th>Employee Code</th>
                            <th>Name</th>
                            <th>Basic Salary</th>
                            <th>Gross Pay</th>
                            <th>Allowances</th>
                            <th>13th Month</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td>{{$employee->emp_code}}</td>
                                <td>{{$employee->name}}</td>
                                <td>{{$employee->month_pay}}</td>
                                <td>{{($payrolls->where('emp_code',$employee->emp_code)->sum('gross_pay'))+ $employee->semi_month_pay}}</td>
                                <td>{{($payrolls->where('emp_code',$employee->emp_code)->sum('meal_allowance')) + ($payrolls->where('emp_code',$employee->emp_code)->sum('salary_allowance')) + ($payrolls->where('emp_code',$employee->emp_code)->sum('oot_allowance'))+ ($payrolls->where('emp_code',$employee->emp_code)->sum('inc_allowance'))+ ($payrolls->where('emp_code',$employee->emp_code)->sum('rel_allowance'))+ ($payrolls->where('emp_code',$employee->emp_code)->sum('disc_allowance'))+ ($payrolls->where('emp_code',$employee->emp_code)->sum('trans_allowance'))+ ($payrolls->where('emp_code',$employee->emp_code)->sum('load_allowance'))}}</td>
                                <td>
                                    @php
                                        $gross_pay_total = ($payrolls->where('emp_code',$employee->emp_code)->sum('gross_pay'))+ $employee->semi_month_pay - ($payrolls->where('emp_code',$employee->emp_code)->sum('overtime'));
                                        $total_allowances = ($payrolls->where('emp_code',$employee->emp_code)->sum('meal_allowance')) + ($payrolls->where('emp_code',$employee->emp_code)->sum('salary_allowance')) + ($payrolls->where('emp_code',$employee->emp_code)->sum('oot_allowance'))+ ($payrolls->where('emp_code',$employee->emp_code)->sum('inc_allowance'))+ ($payrolls->where('emp_code',$employee->emp_code)->sum('rel_allowance'))+ ($payrolls->where('emp_code',$employee->emp_code)->sum('disc_allowance'))+ ($payrolls->where('emp_code',$employee->emp_code)->sum('trans_allowance'))+ ($payrolls->where('emp_code',$employee->emp_code)->sum('load_allowance'));
                                    @endphp
                                    {{round(($gross_pay_total-$total_allowances)/12,2)}}
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
@endsection

