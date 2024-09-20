@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Pay Reg</h4>
                <p class="card-description">
                    <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#uploadPayReg">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      Upload Pay Reg
                    </button>
                  </p>
                <div class="table-responsive">
                  <table id="table-payroll" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Payroll Name</th>
                            <th>Payroll Date</th>
                            <th>Cutoff Date</th>
                            <th>Payment Date</th>
                            <th>Company</th>
                            <th>Employee Count</th>
                            <th>Total Gross Pay</th>
                            <th>Tax Total</th>
                            <th>Total Deduction</th>
                            <th>Net Pay Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        </tr>
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
