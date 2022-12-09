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
                  <table class="table table-hover table-bordered  " border='1' id='monthly_pay'>
                    <thead>
                        <tr>
                            
                            <th>Semi Month</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Location</th>
                            <th>Account Number</th>
                            <th>Bank</th>
                            @foreach($dates as $date)
                            <th>{{date('M d, Y',strtotime($date))}}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                
                                <td>{{$employee->semi_month_pay}}</td>
                                <td>{{$employee->name}}</td>
                                <td>OBANANA</td>
                                <td>{{$employee->department}}</td>
                                <td>{{$employee->location}}</td>
                                <td>{{$employee->bank_acctno}}</td>
                                <td>{{$employee->bank}}</td>
                                @foreach($dates as $date)
                                <td>
                                    
                                    @php
                                        $pay = $payrolls->where('date_period',$date)->where('emp_code',$employee->emp_code)->first();
                                        // dd($date);
                                        $month = 0;
                                        if($pay != null)
                                        {
                                            $month = $pay->gross_pay-$pay->load_allowance-$pay->trans_allowance-$pay->rel_allowance-$pay->oot_allowance-$pay->salary_allowance-$pay->meal_allowance-$pay->overtime;
                                        }
                                    @endphp
                                    {{$month}}
                                </td>
                                @endforeach
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

