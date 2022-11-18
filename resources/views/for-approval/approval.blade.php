@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row grid-margin'>
          <div class='col-lg-2 '>
            <div class="card card-tale">
              <div class="card-body">
                <div class="media">                
                  <div class="media-body">
                    <h4 class="mb-4">Leave</h4>
                    <h2 class="card-text">0</h2>
                  </div>
                </div>
              </div>
            </div>
          </div> 
          <div class='col-lg-2'>
            <div class="card card-dark-blue">
              <div class="card-body">
                <div class="media">                
                  <div class="media-body">
                    <h4 class="mb-4">Overtime</h4>
                    <h2 class="card-text">0</h2>
                  </div>
                </div>
              </div>
            </div>
          </div> 
          <div class='col-lg-2'>
            <div class="card card-light-blue">
              <div class="card-body">
                <div class="media">                
                  <div class="media-body">
                    <h4 class="mb-4">Work From Home</h4>
                    <h2 class="card-text">0</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class='col-lg-2'>
            <div class="card card-light-danger">
              <div class="card-body">
                <div class="media">                
                  <div class="media-body">
                    <h4 class="mb-4">Official Business</h4>
                    <h2 class="card-text">0</h2>
                  </div>
                </div>
              </div>
            </div>
          </div> 
          <div class='col-lg-2'>
            <div class="card card-tale">
              <div class="card-body">
                <div class="media">                
                  <div class="media-body">
                    <h4 class="mb-4">DTR Correction</h4>
                    <h2 class="card-text">0</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class='col-lg-2'>
            <div class="card text-success">
              <div class="card-body">
                <div class="media">                
                  <div class="media-body">
                    <h4 class="mb-4">Approved</h4>
                    <h2 class="card-text">0</h2>
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
                <h4 class="card-title">For Approval</h4>
                <p class="card-description">
                  {{-- <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#applyovertime">
                    <i class="ti-check btn-icon-prepend"></i>                                                    
                  </button>
                  <button type="button" class="btn btn-outline-danger btn-icon-text" data-toggle="modal" data-target="#applyovertime">
                    <i class="ti-close btn-icon-prepend"></i>                                                    
                  </button>                   --}}
                </p>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>Employee Name</th>
                        <th>Form Type</th>
                        <th>Date</th>
                        <th>Reason/Remarks</th> 
                        <th>Attachment</th>
                        <th>Action </th> 
                      </tr>
                    </thead>
                    <tbody> 
                      @foreach ($form_approvals as $form_approval)
                        @foreach($form_approval->user_info->emp_leave as $leave)
                          <tr>
                            <td>{{$form_approval->user_info->name}}</td>
                            <td>{{$leave->leave->leave_type}}</td>
                            <td>{{date('M d, Y', strtotime($leave->date_from))}} - {{date('M d, Y', strtotime($leave->date_to))}}</td>
                            <td>{{$leave->reason}}</td>
                            <td><a href="{{url($leave->attachment)}}" target='_blank' class="text-start"><button type="button" class="btn btn-outline-info btn-sm ">View Attachment</button></a>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#applyovertime">
                                <i class="ti-check btn-icon-prepend"></i>                                                    
                              </button>
                              <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#applyovertime">
                                <i class="ti-close btn-icon-prepend"></i>                                                    
                              </button> 
                            </td>                            
                          </tr>  
                        @endforeach     
                        @foreach($form_approval->user_info->emp_ot as $ot_file)
                          <tr>
                            <td>{{$form_approval->user_info->name}}</td>
                            <td>Overtime</td>   
                            <td>{{date('M d, Y', strtotime($ot_file->ot_date))}}</td>
                            <td>{{$ot_file->remarks}}</td>
                            <td><a href="{{url($ot_file->attachment)}}" target='_blank' class="text-start"><button type="button" class="btn btn-outline-info btn-sm ">View Attachment</button></a></td>                                                     
                            <td>
                              <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#applyovertime">
                              <i class="ti-check btn-icon-prepend"></i>                                                    
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#applyovertime">
                              <i class="ti-close btn-icon-prepend"></i>                                                    
                            </button> 
                          </td>   
                          </tr>  
                        @endforeach 
                        @foreach($form_approval->user_info->emp_wfh as $wfh_file)
                          <tr>
                            <td>{{$form_approval->user_info->name}}</td>
                            <td>Work From Home</td>     
                            <td>{{date('M d, Y', strtotime($wfh_file->date_from))}} - {{date('M d, Y', strtotime($wfh_file->date_to))}}</td>
                            <td>{{$wfh_file->remarks}}</td>
                            <td><a href="{{url($wfh_file->attachment)}}" target='_blank' class="text-start"><button type="button" class="btn btn-outline-info btn-sm ">View Attachment</button></a></td>
                            <td>
                              <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#applyovertime">
                              <i class="ti-check btn-icon-prepend"></i>                                                    
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#applyovertime">
                              <i class="ti-close btn-icon-prepend"></i>                                                    
                            </button> 
                          </td>   
                          </tr>  
                        @endforeach 
                        @foreach($form_approval->user_info->emp_ob as $ob_file)
                          <tr>
                            <td>{{$form_approval->user_info->name}}</td>
                            <td>Official Business</td>     
                            <td>{{date('M d, Y', strtotime($ob_file->date_from))}} - {{date('M d, Y', strtotime($ob_file->date_to))}}</td>
                            <td>{{$ob_file->remarks}}</td>
                            <td><a href="{{url($ob_file->attachment)}}" target='_blank' class="text-start"><button type="button" class="btn btn-outline-info btn-sm ">View Attachment</button></a></td>
                            <td>
                              <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#applyovertime">
                              <i class="ti-check btn-icon-prepend"></i>                                                    
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#applyovertime">
                              <i class="ti-close btn-icon-prepend"></i>                                                    
                            </button> 
                          </td>   
                          </tr>  
                        @endforeach
                        @foreach($form_approval->user_info->emp_dtr as $dtr_file)
                          <tr>
                            <td>{{$form_approval->user_info->name}}</td>
                            <td>DTR Correction</td>     
                            <td>{{date('M d, Y', strtotime($dtr_file->dtr_date))}}</td>
                            <td>{{$dtr_file->remarks}}</td>
                            <td><a href="{{url($dtr_file->attachment)}}" target='_blank' class="text-start"><button type="button" class="btn btn-outline-info btn-sm ">View Attachment</button></a></td>
                            <td>
                              <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#applyovertime">
                              <i class="ti-check btn-icon-prepend"></i>                                                    
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#applyovertime">
                              <i class="ti-close btn-icon-prepend"></i>                                                    
                            </button> 
                          </td>   
                          </tr>  
                        @endforeach                                                                                                 
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

