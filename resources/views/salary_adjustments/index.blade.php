@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Salary Adjustment</h4>
                <p class="card-description">
                    <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newPayrollSalaryAdjustment">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      Add new Salary Adjustment 
                    </button>
                </p>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Name</th> 
                        <th>Effectivity Date</th> 
                        <th>Amount</th> 
                        <th>Type</th> 
                        <th>Reason</th> 
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  
                        @foreach($salary_adjustments as $salary_adjustment)
                        <tr class='cursor-pointer' data-toggle="modal" data-target="#editSalaryAdjustment{{$salary_adjustment->id}}">
                            <td>{{$salary_adjustment->id}}</td>
                            <td>{{$salary_adjustment->user->name}}</td>
                            <td>{{$salary_adjustment->effectivity_date}}</td>
                            <td>{{$salary_adjustment->amount}}</td>
                            <td>{{$salary_adjustment->type}}</td>
                            <td>{{$salary_adjustment->reason}}</td>
                            <td>
                              @if (checkUserPrivilege('settings_edit',auth()->user()->id) == 'yes')
                              <button type="button" class="btn btn-info btn-rounded btn-icon" href="#edit_holiday{{$salary_adjustment->id}}" data-toggle="modal" title='EDIT'>
                                  <i class="ti-pencil-alt"></i>
                              </button>
                              @endif
                              @if (checkUserPrivilege('settings_delete',auth()->user()->id) == 'yes')
                              <a href="delete-payroll-salary-adjusment/{{$salary_adjustment->id}}">
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
        </div>
    </div>
</div>
@endsection
