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
                        <th>Used</th>
                        <th>Pending</th>
                        <th>Balance</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($leave_balances as $leave)
                      <tr>
                        <td>{{$leave->leave->leave_type}}</td>
                        <td>0</td>
                        <td>0</td>
                        <td>{{$leave->earned_leave}}</td>
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
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#applyLeave">
                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                    Apply Leave
                  </button>
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
                        <th>Leave Count () </th>
                        <th>Status </th>
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
                                  
                        <td>{{ $employee_leave->reason }}</td>
                        <td>{{get_count_days($employee_leave->schedule,$employee_leave->date_from,$employee_leave->date_to)}}</td>
                        <td id="tdStatus{{ $employee_leave->id }}">
                          @if ($employee_leave->status == 'Pending')
                            <label class="badge badge-warning">{{ $employee_leave->status }}</label>
                          @elseif($employee_leave->status == 'Approved')
                            <label class="badge badge-success">{{ $employee_leave->status }}</label>
                          @elseif($employee_leave->status == 'Rejected' or $employee_leave->status == 'Cancelled')
                            <label class="badge badge-danger">{{ $employee_leave->status }}</label>
                          @endif                        
                        </td>
                        <td id="tdActionId{{ $employee_leave->id }}" data-id="{{ $employee_leave->id }}">
                          @if ($employee_leave->status == 'Pending' and $employee_leave->level == 1)
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
                          @elseif ($employee_leave->status == 'Pending' and $employee_leave->level > 1)
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
@endforeach
@foreach ($employee_leaves as $leave)
  @include('forms.leaves.view_leave') 
@endforeach
  @include('forms.leaves.apply_leave') 
@endsection
@section('LeaveScript')
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
