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
                      <h4 class="mb-4">For Manager Ratings</h4>
                      <a href="/for-performance-review?status=For Approval" class="h2 card-text text-white">{{$for_manager_ratings}}</a>
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
                      <h4 class="mb-4">For Acceptance</h4>
                      <a href="/for-performance-review?status=For Acceptance" class="h2 card-text text-white">{{$for_acceptance}}</a>
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
                      <h4 class="mb-4">Accepted</h4>
                      <a href="/for-performance-review?status=Accepted" class="h2 card-text text-white">{{$accepted}}</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>            
            <div class='col-lg-2 mt-2'>
              <div class="card card-success">
                <div class="card-body">
                  <div class="media">                
                    <div class="media-body">
                      <h4 class="mb-4">Summary of Ratings</h4>
                      <a href="/for-performance-review?status=Summary of Ratings" class="h2 card-text text-dark">{{$summary_of_ratings}}</a>
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
                  <h4 class="card-title">For Approval Performance Evaluations</h4>
                  <form method='get' onsubmit='show();' enctype="multipart/form-data">
                    <div class=row>
                      <div class='col-md-2'>
                        <div class="form-group">
                          <label class="text-right">From</label>
                          <input type="date" value='{{$from}}' class="form-control form-control-sm" name="from" onchange='get_min(this.value);' />
                        </div>
                      </div>
                      <div class='col-md-2'>
                        <div class="form-group">
                          <label class="text-right">To</label>
                          <input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to" />
                        </div>
                      </div>
                      <div class='col-md-2 mr-2'>
                        <div class="form-group">
                          <label class="text-right">Status</label>
                          <select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status' required>
                            <option value="">-- Select Status --</option>
                            <option value="For Approval" @if ('For Approval' == $status) selected @endif>For Manager Ratings</option>
                            <option value="For Acceptance" @if ('For Acceptance' == $status) selected @endif>For Acceptance</option>
                            <option value="Accepted" @if ('Accepted' == $status) selected @endif>Accepted</option>
                            <option value="Summary of Ratings" @if ('Summary of Ratings' == $status) selected @endif>Summary of Ratings</option>
                          </select>
                        </div>
                      </div>
                      <div class='col-md-2'>
                        <button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Filter</button>
                      </div>
                    </div>
                  </form>
                  
  
                  <button class="btn btn-success btn-sm mb-2" id="approveAllBtn" style="display: none;">Approve</button>
                  <button class="btn btn-danger btn-sm mb-2" id="disApproveAllBtn" style="display: none;">Disapprove</button>
  
                  <div class="table-responsive">
                    <table id="pprDataTable" class="table table-hover table-bordered tablewithSearch">
                      <thead>
                        <tr>
                          <th>Employee Name</th>
                          <th>Self Assessment Date</th>
                          <th>Calendar Year</th>
                          <th>Period</th>
                          <th>Immediate Superior</th>
                          <th>Status</th>
                          <th>Action </th> 
                        </tr>
                      </thead>
                      <tbody> 
                          @foreach ($performance_evaluations as $form_approval)
                          <tr>
                            <td>
                                <strong>{{ $form_approval->employee->first_name . ' ' . $form_approval->employee->last_name}}</strong> <br>
                                <small>Position : {{$form_approval->user->employee->position}}</small> <br>
                                <small>Company : {{$form_approval->user->employee->company->company_name}}</small> <br>
                                <small>Department : {{ $form_approval->user->employee->department ? $form_approval->user->employee->department->name : ""}}</small>
                            </td>
                            <td>{{ date('Y-m-d h:i A',strtotime($form_approval->self_assessment_is_posted_date))}}</td>
                            <td>{{$form_approval->ppr ? $form_approval->ppr->calendar_year : ""}}</td>
                            <td>{{$form_approval->ppr ? $form_approval->ppr->period : ""}}</td>
                            <td id="tdStatus{{ $form_approval->id }}">
                              @foreach($form_approval->approver as $approver)
                                @if($approver->level == 1)
                                  {{$approver->approver_info->name}}
                                @endif
                              @endforeach
                            </td>
                            <td>
                                {{$form_approval->status == 'For Approval' ? "For Manager Ratings" : $form_approval->status}} 
                                @if($form_approval->user_acceptance == 1)
                                <br>
                                  @if($form_approval->user_acceptance_status == 'Acknowledge')
                                    <span class="badge badge-warning mt-2">{{$form_approval->user_acceptance_status}}</span>
                                  @elseif($form_approval->user_acceptance_status == 'Agree')
                                    <span class="badge badge-success mt-2">{{$form_approval->user_acceptance_status}}</span>
                                  @endif
                                @endif
                                
                            </td>
                            <td align="center" id="tdActionId{{ $form_approval->id }}" data-id="{{ $form_approval->id }}">
                              @if($status == 'Summary of Ratings')
                                <a href="/take-performance-plan-review/{{$form_approval->ppr->id}}?user_id={{$form_approval->user_id}}&method=Summary Assessment" target="_blank" class="btn btn-primary btn-sm">Summary Ratings</a>
                              @elseif($status == 'Accepted')
                                <a href="/show-performance-plan-review/{{$form_approval->ppr->id}}?user_id={{$form_approval->user_id}}&method=Summary Assessment" target="_blank" class="btn btn-primary btn-sm">Show Assessment</a>
                                
                              @else
                                <a href="/take-performance-plan-review/{{$form_approval->ppr->id}}?user_id={{$form_approval->user_id}}&method=Manager Assessment" target="_blank" class="btn btn-primary btn-sm">Manager Ratings</a>
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
    </div>
</div>
@endsection