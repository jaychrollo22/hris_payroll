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
                    <h4 class="mb-4">For Appoval</h4>
                    <a href="/for-overtime?status=Pending" class="h2 card-text text-white">{{$for_approval}}</a>
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
                    <a href="/for-overtime?status=Approved" class="h2 card-text text-white">{{$approved}}</a>
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
                    <a href="/for-overtime?status=Declined" class="h2 card-text text-white">{{$declined}}</a>
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
                <h4 class="card-title">For Approval Overtime</h4>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>Employee Name</th>
                        <th>Date Filed</th>
                        <th>OT Details</th> 
                        <th>OT Requested (Hrs)</th>
                        <th>Break (Hrs)</th>
                        <th>OT Approved (Hrs)</th>
                        <th>Total Approved (Hrs)</th>
                        <th>Remarks </th>
                        <th>Approvers </th>
                        <th>Status </th>
                        <th>Action </th> 
                      </tr>
                    </thead>
                    <tbody> 
                      @foreach ($overtimes as $form_approval)
                      <tr>
                        <td>
                            <strong>{{$form_approval->user->name}}</strong> <br>
                            <small>Position : {{$form_approval->user->employee->position}}</small> <br>
                            <small>Location : {{$form_approval->user->employee->location}}</small> <br>
                            <small>Department : {{ $form_approval->user->employee->department ? $form_approval->user->employee->department->name : ""}}</small>
                        </td>
                        <td>{{date('d/m/Y', strtotime($form_approval->created_at))}}</td>
                        <td>
                          Date : {{date('d/m/Y', strtotime($form_approval->ot_date))}} <br>
                          Time : {{date('h:i A', strtotime($form_approval->start_time))}} - {{date('h:i A', strtotime($form_approval->end_time))}}
                        </td>
                        <td>
                          @php
                            $startTime = new DateTime($form_approval->start_time);
                            $endTime = new DateTime($form_approval->end_time);

                            // Calculate the time difference
                            $timeDifference = $endTime->diff($startTime);
                            // Convert the time difference to decimal hours
                            $total = ($timeDifference->days * 24) + $timeDifference->h + ($timeDifference->i / 60);
                          @endphp
                          {{ number_format($total,2)}}</td>
                        <td>{{$form_approval->break_hrs}}</td>
                        <td>{{$form_approval->ot_approved_hrs}}</td>
                        <td>{{$form_approval->ot_approved_hrs - $form_approval->break_hrs}}</td>

                        <td>
                          <p title="{{$form_approval->remarks}}" style="width: 250px;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                            {{$form_approval->remarks}}
                          </p>
                        </td>
                        <td id="tdStatus{{ $form_approval->id }}">
                          @foreach($form_approval->approver as $approver)
                            @if($form_approval->level >= $approver->level)
                              @if ($form_approval->level == 0 && $form_approval->status == 'Declined')
                              {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                              @else
                                {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                              @endif
                            @else
                              @if ($form_approval->status == 'Declined')
                                {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                              @else
                                {{$approver->approver_info->name}} -  <label class="badge badge-warning mt-1">Pending</label>
                              @endif
                            @endif<br> 
                          @endforeach
                        </td>
                        <td>
                          @if ($form_approval->status == 'Pending')
                            <label class="badge badge-warning">{{ $form_approval->status }}</label>
                          @elseif($form_approval->status == 'Approved')
                            <label class="badge badge-success" title="{{$form_approval->approval_remarks}}">{{ $form_approval->status }}</label>
                          @elseif($form_approval->status == 'Declined' || $form_approval->status == 'Cancelled')
                            <label class="badge badge-danger" title="{{$form_approval->approval_remarks}}">{{ $form_approval->status }}</label>
                          @endif  
                        </td>
                        <td align="center" id="tdActionId{{ $form_approval->id }}" data-id="{{ $form_approval->id }}">

                          @foreach($form_approval->approver as $k => $approver)
                            @if($approver->approver_id == $approver_id && $form_approval->level == $k && $form_approval->status == 'Pending')
                              <button type="button" class="btn btn-success btn-sm" id="{{ $form_approval->id }}" data-target="#approve-ot-hrs-{{ $form_approval->id }}" data-toggle="modal" title='Approve'>
                                <i class="ti-check btn-icon-prepend"></i>                                                    
                              </button>
                              <button type="button" class="btn btn-danger btn-sm" id="{{ $form_approval->id }}" data-target="#overtime-declined-remarks-{{ $form_approval->id }}" data-toggle="modal" title='Decline'>
                                <i class="ti-close btn-icon-prepend"></i>                                                    
                              </button> 
                            @endif<br> 
                          @endforeach

                          
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

@foreach ($overtimes as $overtime)
  @include('for-approval.remarks.overtime_approved_remarks')
  @include('for-approval.remarks.overtime_declined_remarks')
@endforeach 


@endsection

