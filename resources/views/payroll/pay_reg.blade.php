@extends('layouts.header')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Payroll 
                    <button type="button" class="btn btn-outline-danger btn-icon-text btn-sm"  data-toggle="modal" data-target="#payrollD">
                        <i class="ti-upload btn-icon-prepend"></i>                                                    
                        Upload
                    </button>
                </h4>
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
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                        <tr>
                            <th>Payroll Date</th>
                            <th>Date Uploaded</th>
                            <th>Employee Count</th>
                            <th>Total Gross Pay</th>
                            <th>Tax Total</th>
                            <th>Total Deduction</th>
                            <th>Net Pay Total</th>
                            {{-- <th>SSS Reg EE Total</th>
                            <th>SSS MP EE Total</th>
                            <th>PHIC EE Total</th>
                            <th>HDMF EE Total</th>
                            <th>SSS Reg Er Total</th>
                            <th>SSS Mpf Er Total</th>
                            <th>SSS Ec Total</th>
                            <th>Phic ER Total</th>
                            <th>HDMF ER Total</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
        
    </div>
</div>

@include('payroll.upload_payroll')
@endsection
