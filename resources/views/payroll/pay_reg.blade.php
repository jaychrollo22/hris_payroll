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
                <h4 class="card-title">Payroll 
                    <button type="button" class="btn btn-outline-danger btn-icon-text btn-sm"  data-toggle="modal" data-target="#payrollD">
                        <i class="ti-upload btn-icon-prepend"></i>                                                    
                        Upload
                    </button>
                    
                    <a href='{{url('payroll.xlsx')}}' target='_blank'><button type="button" class="btn btn-primary btn-icon-text btn-sm">
                        <i class="ti-file btn-icon-prepend "></i>
                        Download Format
                    </button></a>
                </h4>
               
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                        <tr>
                            <th>Payroll Date</th>
                            <!-- <th>Date Generated</th> -->
                            <th>Employee Count</th>
                            <th>Total Gross Pay</th>
                            <th>Tax Total</th>
                            <th>Total Deduction</th>
                            <th>Net Pay Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payrolls as $payroll)
                            <tr>
                                <td>{{date('M d, Y',strtotime($payroll->date_from))}} - {{date('M d, Y',strtotime($payroll->date_to))}}</td>
                                <!-- <td>{{date('M d, Y',strtotime($payroll->created_at))}}</td> -->
                                <td><a href='#' data-toggle="modal" data-target="#view_payroll{{$payroll->date_from}}"> {{count($payroll_employees->where('date_from',$payroll->date_from))}} </a></td>
                                <td>{{number_format($payroll_employees->where('date_from',$payroll->date_from)->sum('gross_pay'),2)}}</td>
                                <td>{{number_format($payroll_employees->where('date_from',$payroll->date_from)->sum('witholding_tax'),2)}}</td>
                                <td>{{number_format($payroll_employees->where('date_from',$payroll->date_from)->sum('total_deduction'),2)}}</td>
                                <td>{{number_format($payroll_employees->where('date_from',$payroll->date_from)->sum('netpay'),2)}}</td>
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
    @foreach($payrolls as $payroll)
        <!-- <div>{{$payroll->date_from}}</div> -->
        @include('payroll.view_payroll')   
    @endforeach
    @foreach($attendances as $att)
        @include('payroll.view_attendances')   
    @endforeach
    @include('payroll.upload_payroll')
    <!-- @include('payroll.upload_attendance') -->
@endsection

