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
                    <a href="/for-official-business?status=Pending" class="h2 card-text text-white">{{$for_approval}}</a>
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
                    <a href="/for-official-business?status=Approved" class="h2 card-text text-white">{{$approved}}</a>
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
                    <a href="/for-official-business?status=Declined" class="h2 card-text text-white">{{$declined}}</a>
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
                <h4 class="card-title">For Approval OB</h4>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>Employee Name</th>
                        <th>Date Filed</th>
                        <th>Date</th>
                        <th>Time In-Out</th>
                        <th>Destination</th>
                        <th>Person/Company to see</th>
                        <th>Purpose</th>
                        <th>Approvers</th> 
                        <th>Attachment</th>
                        <th>Status</th>
                        <th>Action </th> 
                      </tr>
                    </thead>
                    <tbody> 
                      @foreach ($obs as $form_approval)
                      <tr>
                        <td>
                            <strong>{{$form_approval->user->name}}</strong> <br>
                            <small>Position : {{$form_approval->user->employee->position}}</small> <br>
                            <small>Location : {{$form_approval->user->employee->location}}</small> <br>
                            <small>Department : {{ $form_approval->user->employee->department ? $form_approval->user->employee->department->name : ""}}</small>
                        </td>
                        <td> {{ $form_approval->created_at }} </td>
                        <td> {{ date('d/m/Y ', strtotime($form_approval->applied_date)) }}</td>
                        <td> {{ date('H:i', strtotime($form_approval->date_from)) }} - {{ date('H:i', strtotime($form_approval->date_to)) }}  </td>
                        <td> {{$form_approval->destination}}</td>
                        <td> {{$form_approval->persontosee}}</td>
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
                          @if($form_approval->attachment)
                          <a href="{{url($form_approval->attachment)}}" target='_blank' class="text-start"><button type="button" class="btn btn-outline-info btn-sm ">View Attachment</button></a>
                          @endif
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
                              <button type="button" class="btn btn-success btn-sm" id="{{ $form_approval->id }}" data-target="#ob-approved-remarks-{{ $form_approval->id }}" data-toggle="modal" title="Approve">
                                <i class="ti-check btn-icon-prepend"></i>                                                    
                              </button>
                              <button type="button" class="btn btn-danger btn-sm" id="{{ $form_approval->id }}" data-target="#ob-declined-remarks-{{ $form_approval->id }}" data-toggle="modal" title="Decline">
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
@foreach ($obs as $ob)
  @include('for-approval.remarks.ob_approved_remarks')
  @include('for-approval.remarks.ob_declined_remarks')
@endforeach

@endsection

