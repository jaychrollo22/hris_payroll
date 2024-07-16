@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row grid-margin'>
          
          <div class='col-lg-2 mt-2'>
            <div class="card card-tale">
              <div class="card-body">
                <div class="media">                
                  <div class="media-body">
                    <h4 class="mb-4">For Approval</h4>
                    <a href="/hr-performance-plan-review?status=For Review" class="h2 card-text text-white">{{$for_approval}}</a>
                  </div>
                </div>
              </div>
            </div>
          </div> 
          <div class='col-lg-2 mt-2'>
            <div class="card card-dark-blue">
              <div class="card-body">
                <div class="media">                
                  <div class="media-body">
                    <h4 class="mb-4">Approved</h4>
                    <a href="/hr-performance-plan-review?status=Approved" class="h2 card-text text-white">{{$approved}}</a>
                  </div>
                </div>
              </div>
            </div>
          </div> 
          <div class='col-lg-2 mt-2'>
            <div class="card card-light-danger">
              <div class="card-body">
                <div class="media">                
                  <div class="media-body">
                    <h4 class="mb-4">Declined / Rejected</h4>
                    <a href="/hr-performance-plan-review?status=Declined" class="h2 card-text text-white">{{$declined}}</a>
                  </div>
                </div>
              </div>
            </div>
          </div>     
          <div class='col-lg-2 mt-2'>
            <div class="card card-primary">
              <div class="card-body">
                <div class="media">                
                  <div class="media-body">
                    <h4 class="mb-4">Draft</h4>
                    <a href="/hr-performance-plan-review?status=Draft" class="h2 card-text text-dark">{{$draft}}</a>
                  </div>
                </div>
              </div>
            </div>
          </div>        
        </div>
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

                <a href="/export-ppr?company={{$company}}&calendar_date={{$performance_plan_period}}&status={{$status}}&period_ppr={{$period_ppr}}" class="btn btn-outline-primary btn-icon-text btn-sm text-center float-right mr-2" title="Export PPR"><i class="ti-arrow-down btn-icon-prepend"></i></a>
                
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
                    <div class='col-md-2'>
                      <div class="form-group">
                        <select data-placeholder="Calendar" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='performance_plan_period'>
                            <option value="">-- Calendar --</option>
                            @foreach($performance_plan_periods as $period)
                            <option value="{{$period->period}}" @if ($period->period == $performance_plan_period) selected @endif>{{$period->period}}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class='col-md-2 mr-2'>
                      <div class="form-group">
                        <select data-placeholder="Period" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='period_ppr'>
                          <option value="">-- Period --</option>
                          <option value="ANNUAL" @if ('ANNUAL' == $period_ppr) selected @endif>ANNUAL</option>
                          <option value="MIDYEAR" @if ('MIDYEAR' == $period_ppr) selected @endif>MIDYEAR</option>
                          <option value="PROBATIONARY" @if ('PROBATIONARY' == $period_ppr) selected @endif>PROBATIONARY</option>
                          <option value="SPECIAL" @if ('SPECIAL' == $period_ppr) selected @endif>SPECIAL</option>
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
                          <option value="Declined" @if ('Declined' == $status) selected @endif>Declined / Rejected</option>
                          {{-- For Self Ratings --}}
                          <option value="Pending Self Ratings" @if ('Pending Self Ratings' == $status) selected @endif>Pending Self Ratings</option>
                          <option value="Ongoing Self Ratings" @if ('Ongoing Self Ratings' == $status) selected @endif>Ongoing Self Ratings</option>
                          <option value="For Approval" @if ('For Approval' == $status) selected @endif>For Manager Ratings</option>
                          <option value="For Acceptance" @if ('For Acceptance' == $status) selected @endif>For Acceptance</option>
                          <option value="Summary of Ratings" @if ('Summary of Ratings' == $status) selected @endif>For Summary of Ratings</option>
                          <option value="Completed" @if ('Completed' == $status) selected @endif>Completed</option>
                        </select>
                      </div>
                    </div>
                    <div class='col-md-2 mr-2'>
                      <div class="form-group">
                        <input type="date" name="change_from" class="form-control" value="{{$change_from}}" title="Change From">
                      </div>
                    </div>
                    <div class='col-md-2 mr-2'>
                      <div class="form-group">
                        <input type="date" name="change_to" class="form-control" value="{{$change_to}}" title="Change To">
                      </div>
                    </div>
                    <div class='col-md-2'>
                      <button type="submit" class="btn btn-primary">Filter</button>
                      <a href="/hr-performance-plan-review" class="btn btn-warning">Clear Filter</a>
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
                        <th>Approver</th>
                        <th>Review Date</th>
                        <th>PPR Status</th>
                        <th>Performance Ratings Status</th>
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
                          <td>{{ date('Y-m-d h:i A',strtotime($eval->created_at))}}</td>
                          <td>{{ $eval->calendar_year}}</td>
                          <td>{{ $eval->period}}</td>
                          <td id="tdStatus{{ $eval->id }}">
                            @foreach($eval->approver as $approver)
                              @if($eval->level >= $approver->level)
                                  @if ($eval->level == 0 && $eval->status == 'Declined')
                                  {{$approver->approver_info ? $approver->approver_info->name : ""}} -  <label class="badge badge-danger mt-1">Declined</label>
                                  @else
                                  {{$approver->approver_info ? $approver->approver_info->name : ""}} -  <label class="badge badge-success mt-1">Approved</label>
                                  @endif
                              @else
                                @if ($eval->status == 'Declined')
                                  {{$approver->approver_info ? $approver->approver_info->name : ""}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @elseif ($eval->status == 'Draft')
                                  {{$approver->approver_info ? $approver->approver_info->name : ""}}
                                @else
                                  {{$approver->approver_info ? $approver->approver_info->name : ""}} -  <label class="badge badge-warning mt-1">For Review</label>
                                @endif
                              @endif<br> 
                            @endforeach
                          </td>
                          <td>{{ $eval->approved_by_date ? date('Y-m-d',strtotime($eval->approved_by_date)) : ""}}</td>
                          <td>
                            @if ($eval->status == 'Draft')
                              <label class="badge badge-default">{{ $eval->status }}</label>
                            @elseif ($eval->status == 'For Review')
                              <label class="badge badge-warning">{{ $eval->status }}</label>
                            @elseif($eval->status == 'Approved')
                              <label class="badge badge-success" title="{{$eval->approval_remarks}}">{{ $eval->status }}</label>
                            @elseif($eval->status == 'Declined' || $eval->status == 'Cancelled')
                              <label class="badge badge-danger" title="{{$eval->approval_remarks}}">{{ $eval->status }}</label>
                            @endif
                          </td>
                          <td>

                            @php
                                $performance_rating_status = '';
                                if($eval->ppr_score){
                                  $ppr_status = $eval->ppr_score->status;
                                  if($ppr_status == 'For Approval'){
                                    $performance_rating_status = 'For Managers Ratings';
                                  }
                                  else{
                                    if($eval->ppr_score->self_assessment_is_posted == null){
                                      $performance_rating_status = "Ongoing Self Ratings";
                                    }
                                    elseif($eval->ppr_score->status == "Accepted" && $eval->ppr_score->summary_of_ratings_is_posted == null){
                                      $performance_rating_status = "For Summary of Ratings";
                                    }
                                    elseif($eval->ppr_score->status == "Accepted" && $eval->ppr_score->summary_of_ratings_is_posted == "1"){
                                      $performance_rating_status = "Completed";
                                    }
                                    else{
                                      $performance_rating_status = $ppr_status;
                                    }
                                  }
                                }else{
                                  if($eval->status == 'Approved'){
                                    $performance_rating_status = 'Pending Self Ratings';
                                  }
                                }
                            @endphp
                           
                            <label class="badge badge-success" title="{{ $performance_rating_status }}">{{ $performance_rating_status }}</label>
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