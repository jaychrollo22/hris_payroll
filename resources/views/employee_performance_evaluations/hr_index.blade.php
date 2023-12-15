@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">ALL PERFORMANCE PLAN REVIEW (PPR)</h4>
                {{-- <p class="card-description">
                  <a href="/create-performance-plan-review" type="button" class="btn btn-outline-success btn-icon-text">
                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                    Create
                  </a>
                </p> --}}
                
                <form method='get' onsubmit='show();' enctype="multipart/form-data">
                  <div class=row>
                    <div class='col-md-2'>
                      <div class="form-group">
                        <input type="text" class="form-control" name="search" placeholder="Search Name / Biometric Code" value="{{$search}}">
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
                    <div class='col-md-2 mr-2'>
                      <div class="form-group">
                        <select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status'>
                          <option value="">-- Select Status --</option>
                          <option value="Draft" @if ('Draft' == $status) selected @endif>Draft</option>
                          <option value="For Review" @if ('For Review' == $status) selected @endif>For Review</option>
                          <option value="Approved" @if ('Approved' == $status) selected @endif>Approved</option>
                          <option value="Cancelled" @if ('Cancelled' == $status) selected @endif>Cancelled</option>
                        </select>
                      </div>
                    </div>
                    <div class='col-md-2'>
                      <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Filter</button>
                    </div>
                  </div>
                </form>

                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>Employee Number</th>
                        <th>Employee</th>
                        <th>Date Filed</th>
                        <th>Calendar Date</th>
                        <th>PPR Period</th>
                        <th>Review Date</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($employee_performance_evaluation as $eval)
                        <tr>
                          <td>
                            <a href="/show-performance-plan-review/{{$eval->id}}" target="_blank" class="text-success btn-sm text-center" title="View PPR">
                                <i class="ti-pencil btn-icon-prepend"></i>
                            </a>
                            {{ $eval->employee->employee_number}}
                          </td>
                          <td>{{ $eval->employee->first_name . ' ' . $eval->employee->last_name}}</td>
                          <td>{{ date('Y-m-d',strtotime($eval->created_at))}}</td>
                          <td>{{ $eval->calendar_year}}</td>
                          <td>{{ $eval->period}}</td>
                          <td>{{ $eval->approved_by_date ? date('Y-m-d',strtotime($eval->approved_by_date)) : ""}}</td>
                          <td>{{ $eval->status}}</td>
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