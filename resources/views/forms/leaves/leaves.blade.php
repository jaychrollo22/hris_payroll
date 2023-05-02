@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
          <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Leave Type</th>
                        <th>Total</th>
                        <th>Used</th>
                        <th>Balance</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php
                          $is_allowed_to_file_vl = false;
                          $is_allowed_to_file_sl = false;
                          $is_allowed_to_file_sil = false;

                          $is_allowed_to_file_ml = false;
                          $is_allowed_to_file_pl = false;
                          $is_allowed_to_file_spl = false;
                          $is_allowed_to_file_splw = false;
                          $is_allowed_to_file_splvv = false;
                      @endphp

                      @foreach($leave_balances as $leave)
                      <tr>
                        <td>{{$leave->leave->leave_type}}</td>
                        <td>
                          @if ($leave->leave->id == '1')
                            @php
                              $date_from = new DateTime($employee_status->original_date_hired);
                              $date_diff = $date_from->diff(new DateTime(date('Y-m-d')));
                              $total_months = (($date_diff->y) * 12) + ($date_diff->m);

                              $vl_beginning_balance = 0;
                              if($total_months > 11){ //
                                
                                $original_date_hired_m_d = date('m-d',strtotime($employee_status->original_date_hired));
                                $original_date_hired_m = date('m',strtotime($employee_status->original_date_hired));
                                $today = date('Y-m-d');
                                $last_year = date('Y', strtotime('-1 year', strtotime($today)) );
                                $original_date_hired = $last_year . '-' . $original_date_hired_m_d;

                                if($last_year == 2022){
                                  $date_from = new DateTime($original_date_hired);
                                  $date_diff = $date_from->diff(new DateTime(date('Y-m-d')));
                                  $total_months_within = (($date_diff->y) * 12) + ($date_diff->m);

                                  if($total_months_within > 11){
                                    if($original_date_hired_m < date('m', strtotime('-1 month', strtotime($today)))){
                                      $vl_beginning_balance = $leave->count;
                                    }
                                  }else{
                                    $vl_beginning_balance = $leave->count;
                                  }
                                }
                              }else{
                                $vl_beginning_balance = $leave->count;
                              }
                              
                              $total_vl = $vl_beginning_balance + $earned_vl;
                            @endphp
                            {{$total_vl}}
                          @elseif ($leave->leave->id == '2')
                            @php
                              $date_from = new DateTime($employee_status->original_date_hired);
                              $date_diff = $date_from->diff(new DateTime(date('Y-m-d')));
                              $total_months = (($date_diff->y) * 12) + ($date_diff->m);

                              $sl_beginning_balance = 0;
                              if($total_months > 11){ //
                                
                                $original_date_hired_m_d = date('m-d',strtotime($employee_status->original_date_hired));
                                $original_date_hired_m = date('m',strtotime($employee_status->original_date_hired));
                                $today = date('Y-m-d');
                                $last_year = date('Y', strtotime('-1 year', strtotime($today)) );
                                $original_date_hired = $last_year . '-' . $original_date_hired_m_d;

                                if($last_year == 2022){
                                  $date_from = new DateTime($original_date_hired);
                                  $date_diff = $date_from->diff(new DateTime(date('Y-m-d')));
                                  $total_months_within = (($date_diff->y) * 12) + ($date_diff->m);

                                  if($total_months_within > 11){
                                    if($original_date_hired_m < date('m', strtotime('-1 month', strtotime($today)))){
                                      $sl_beginning_balance = $leave->count;
                                    }
                                  }else{
                                    $sl_beginning_balance = $leave->count;
                                  }
                                }
                              }else{
                                $sl_beginning_balance = $leave->count;
                              }
                              
                              $total_sl = $sl_beginning_balance + $earned_sl;
                            @endphp
                              {{$total_sl}}
                          @elseif ($leave->leave->id == '10')
                              {{$earned_sil + $leave->count}}
                          @elseif ($leave->leave->id == '3')
                              {{$leave->count}}
                          @elseif ($leave->leave->id == '4')
                              {{$leave->count}}
                          @elseif ($leave->leave->id == '5')
                              {{$leave->count}}
                          @elseif ($leave->leave->id == '7')
                              {{$leave->count}}
                          @elseif ($leave->leave->id == '8')
                              {{$leave->count}}
                          @endif
                        </td>
                        <td>
                          @if ($leave->leave->id == '1')
                              {{$used_vl}}
                          @elseif ($leave->leave->id == '2')
                              {{$used_sl}}
                          @elseif ($leave->leave->id == '10')
                              {{$used_sil}}
                          @elseif ($leave->leave->id == '3')
                              {{$used_ml}}
                          @elseif ($leave->leave->id == '4')
                              {{$used_pl}}
                          @elseif ($leave->leave->id == '5')
                              {{$used_spl}}
                          @elseif ($leave->leave->id == '7')
                              {{$used_splw}}
                          @elseif ($leave->leave->id == '8')
                              {{$used_splvv}}
                          @endif
                        </td>
                        <td>
                          @if ($leave->leave->id == '1')
                              @php
                                $date_from = new DateTime($employee_status->original_date_hired);
                                $date_diff = $date_from->diff(new DateTime(date('Y-m-d')));
                                $total_months = (($date_diff->y) * 12) + ($date_diff->m);

                                $vl_beginning_balance = 0;
                                if($total_months > 11){ //
                                  
                                  $original_date_hired_m_d = date('m-d',strtotime($employee_status->original_date_hired));
                                  $original_date_hired_m = date('m',strtotime($employee_status->original_date_hired));
                                  $today = date('Y-m-d');
                                  $last_year = date('Y', strtotime('-1 year', strtotime($today)) );
                                  $original_date_hired = $last_year . '-' . $original_date_hired_m_d;

                                  if($last_year == 2022){
                                    $date_from = new DateTime($original_date_hired);
                                    $date_diff = $date_from->diff(new DateTime(date('Y-m-d')));
                                    $total_months_within = (($date_diff->y) * 12) + ($date_diff->m);

                                    if($total_months_within > 11){
                                      if($original_date_hired_m < date('m', strtotime('-1 month', strtotime($today)))){
                                        $vl_beginning_balance = $leave->count;
                                      }
                                    }else{
                                      $vl_beginning_balance = $leave->count;
                                    }
                                  }
                                }else{
                                  $vl_beginning_balance = $leave->count;
                                }
                                
                                $count_vl = ($vl_beginning_balance + $earned_vl) - $used_vl;
                                if($count_vl > 0){
                                  if($total_months > 11){
                                      $is_allowed_to_file_vl = true;
                                  }else{
                                      $is_allowed_to_file_vl = false;
                                  }
                                }else{
                                  $is_allowed_to_file_vl = false;
                                }
                              @endphp
                              {{$count_vl}}
                          @elseif ($leave->leave->id == '2')
                              
                              @php
                                $date_from = new DateTime($employee_status->original_date_hired);
                                $date_diff = $date_from->diff(new DateTime(date('Y-m-d')));
                                $total_months = (($date_diff->y) * 12) + ($date_diff->m);

                                $sl_beginning_balance = 0;
                                if($total_months > 11){ //
                                  
                                  $original_date_hired_m_d = date('m-d',strtotime($employee_status->original_date_hired));
                                  $original_date_hired_m = date('m',strtotime($employee_status->original_date_hired));
                                  $today = date('Y-m-d');
                                  $last_year = date('Y', strtotime('-1 year', strtotime($today)) );
                                  $original_date_hired = $last_year . '-' . $original_date_hired_m_d;

                                  if($last_year == 2022){
                                    $date_from = new DateTime($original_date_hired);
                                    $date_diff = $date_from->diff(new DateTime(date('Y-m-d')));
                                    $total_months_within = (($date_diff->y) * 12) + ($date_diff->m);

                                    if($total_months_within > 11){
                                      if($original_date_hired_m < date('m', strtotime('-1 month', strtotime($today)))){
                                        $sl_beginning_balance = $leave->count;
                                      }
                                    }else{
                                      $sl_beginning_balance = $leave->count;
                                    }
                                  }
                                }else{
                                  $sl_beginning_balance = $leave->count;
                                }
                                
                                $count_sl = ($sl_beginning_balance + $earned_sl) - $used_sl;
                                if($count_sl > 0){
                                  if($total_months > 11){
                                      $is_allowed_to_file_sl = true;
                                  }else{
                                      $is_allowed_to_file_sl = false;
                                  }
                                }else{
                                  $is_allowed_to_file_sl = false;
                                }
                              @endphp
                              {{$count_sl}}
                          @elseif ($leave->leave->id == '10')
                              {{($leave->count + $earned_sil) - $used_sil}}
                              @php
                                $count_sil = ($leave->count + $earned_sil) - $used_sil;
                                if($count_sil > 0){
                                  $is_allowed_to_file_sil = true;
                                }else{
                                  $is_allowed_to_file_sil = false;
                                }
                              @endphp
                          @elseif ($leave->leave->id == '3')
                              {{($leave->count) - $used_ml}}
                              @php
                                $count_ml = ($leave->count) - $used_ml;
                                if($count_ml > 0){
                                  $is_allowed_to_file_ml = true;
                                }else{
                                  $is_allowed_to_file_ml = false;
                                }
                              @endphp
                          @elseif ($leave->leave->id == '4')
                              {{($leave->count) - $used_pl}}
                              @php
                                $count_pl = ($leave->count) - $used_pl;
                                if($count_pl > 0){
                                  $is_allowed_to_file_pl = true;
                                }else{
                                  $is_allowed_to_file_pl = false;
                                }
                              @endphp
                          @elseif ($leave->leave->id == '5')
                                {{($leave->count) - $used_spl}}
                                @php
                                  $count_spl = ($leave->count) - $used_spl;
                                if($count_spl > 0){
                                  $is_allowed_to_file_spl = true;
                                }else{
                                  $is_allowed_to_file_spl = false;
                                }
                              @endphp
                          @elseif ($leave->leave->id == '7')
                              {{($leave->count) - $used_splw}}
                              @php
                                $count_splw = ($leave->count) - $used_splw;
                                if($count_splw > 0){
                                  $is_allowed_to_file_splw = true;
                                }else{
                                  $is_allowed_to_file_splw = false;
                                }
                              @endphp
                          @elseif ($leave->leave->id == '9')
                              {{($leave->count) - $used_splvv}}
                              @php
                                $count_splvv = ($leave->count) - $used_splvv;
                                if($count_splvv > 0){
                                  $is_allowed_to_file_splvv = true;
                                }else{
                                  $is_allowed_to_file_splvv = false;
                                }
                              @endphp
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
          <div class='col-lg-2'>
            <div class="card card-tale">
              <div class="card-body">
                <div class="media">
                
                  <div class="media-body">
                    <h4 class="mb-4">Pending</h4>
                    <h2 class="card-text">{{($employee_leaves->where('status','Pending'))->count()}}</h2>
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
                    <h4 class="mb-4">Denied/Cancelled</h4>
                    <h2 class="card-text">{{($employee_leaves->where('status','Cancelled'))->count()}}</h2>
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
                    <h2 class="card-text">{{($employee_leaves->where('status','Approved'))->count()}}</h2>
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
                <h4 class="card-title">Leaves</h4>
                <p class="card-description">
                  @if($allowed_to_file)
                    <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#applyLeave">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      Apply Leave
                    </button>
                  @else
                    <span class="text-danger">You are not allowed to file a leave yet.</span>
                  @endif
                </p>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>Date Filed</th> 
                        <th>Leave Date</th>
                        <th>Leave Type</th>
                        <th>with Pay </th>
                        <th>Reason </th>
                        <th>Leave Count</th>
                        <th>Status </th>
                        <th>Approvers </th>
                        <th>Action </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($employee_leaves as $employee_leave)
                      <tr>
                        <td>{{date('M d, Y', strtotime($employee_leave->created_at))}}</td>
                        <td>{{date('M d, Y', strtotime($employee_leave->date_from))}} to {{date('M d, Y', strtotime($employee_leave->date_to))}} </td>
                        <td>{{ $employee_leave->leave->leave_type }}</td>
                          @if($employee_leave->withpay == 1)   
                              <td>Yes</td>
                          @else
                              <td>No</td>
                          @endif  
                                  
                        <td>
                          <p title="{{ $employee_leave->reason }}" style="width: 250px;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                            {{ $employee_leave->reason }}
                          </p>
                        </td>
                        <td>{{get_count_days($employee_leave->schedule,$employee_leave->date_from,$employee_leave->date_to)}}</td>
                        <td id="tdStatus{{ $employee_leave->id }}">
                          @if ($employee_leave->status == 'Pending')
                            <label class="badge badge-warning  mt-1">{{ $employee_leave->status }}</label>
                          @elseif($employee_leave->status == 'Approved')
                            <label class="badge badge-success mt-1">{{ $employee_leave->status }}</label>
                          @elseif($employee_leave->status == 'Rejected' or $employee_leave->status == 'Cancelled')
                            <label class="badge badge-danger  mt-1">{{ $employee_leave->status }}</label>
                          @endif                        
                        </td>
                        <td id="tdStatus{{ $employee_leave->id }}">
                          @foreach($employee_leave->approver as $approver)
                            @if($employee_leave->level >= $approver->level)
                              @if ($employee_leave->level == 0 && $employee_leave->status == 'Declined')
                              {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                              @elseif ($employee_leave->level == 1 && $employee_leave->status == 'Declined')
                              {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Approved</label>
                              @else
                                {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                              @endif
                            @else
                              @if ($employee_leave->status == 'Declined')
                                {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                              @elseif ($employee_leave->status == 'Approved')
                                {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                              @else
                                {{$approver->approver_info->name}} -  <label class="badge badge-warning mt-1">Pending</label>
                              @endif
                            @endif<br>
                          @endforeach
                        </td>
                        
                        <td id="tdActionId{{ $employee_leave->id }}" data-id="{{ $employee_leave->id }}">

                          @if ($employee_leave->status == 'Pending' && $employee_leave->level == 0)
                            <button type="button" id="view{{ $employee_leave->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_leave{{ $employee_leave->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>            
                            <button type="button" id="edit{{ $employee_leave->id }}" class="btn btn-info btn-rounded btn-icon"
                              data-target="#edit_leave{{ $employee_leave->id }}" data-toggle="modal" title='Edit'>
                              <i class="ti-pencil-alt"></i>
                            </button>
                            <button title='Cancel' id="{{ $employee_leave->id }}" onclick="cancel(this.id)"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>
                          @elseif ($employee_leave->status == 'Pending' and $employee_leave->level == 1)
                            <button type="button" id="view{{ $employee_leave->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_leave{{ $employee_leave->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>            
                              <button title='Cancel' id="{{ $employee_leave->id }}" onclick="cancel(this.id)"
                                class="btn btn-rounded btn-danger btn-icon">
                                <i class="fa fa-ban"></i>
                              </button>
                          @elseif ($employee_leave->status == 'Approved')   
                            <button type="button" id="view{{ $employee_leave->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_leave{{ $employee_leave->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>   
                            <button title='Cancel' id="{{ $employee_leave->id }}" onclick="cancel(this.id)"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>                           
                          @else
                            <button type="button" id="view{{ $employee_leave->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_leave{{ $employee_leave->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>                                                                               
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
</div>

@php
function get_count_days($data,$date_from,$date_to)
 {
    $data = ($data->pluck('name'))->toArray();
    $count = 0;
    $startTime = strtotime($date_from);
    $endTime = strtotime($date_to);

    for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
      $thisDate = date( 'l', $i ); // 2010-05-01, 2010-05-02, etc
      if(in_array($thisDate,$data)){
          $count= $count+1;
      }
    }
    return($count);
 } 
@endphp  


@foreach ($employee_leaves as $leave)
  @include('forms.leaves.edit_leave')
  @include('forms.leaves.view_leave') 
@endforeach


@include('forms.leaves.apply_leave') 

@endsection

@section('ForApprovalScript')
	<script>
		function cancel(id) {
			var element = document.getElementById('tdActionId'+id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "You want to cancel this leave?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willCancel) => {
					if (willCancel) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "disable-leave/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Leave has been cancelled!", {
									icon: "success",
								}).then(function() {
									document.getElementById("tdStatus" + id).innerHTML =
										"<label class='badge badge-danger'>Cancelled</label>";
                  document.getElementById(dataID).remove();
                  document.getElementById("edit" + dataID).remove();
								});
							}
						})

					} else {
            swal({text:"You stop the cancelation of leave.",icon:"success"});
					}
				});
		}

	</script>
@endsection
