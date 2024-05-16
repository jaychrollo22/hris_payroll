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
                      @if(count($employee_leave_type_balance) > 0)
                        @foreach($employee_leave_type_balance as $leave_balance)

                          @php
                            
                            $additional_leave = 0;
                            $used_leave = 0;

                            if($leave_balance->leave_type_info){
                              $additional_leave = checkEmployeeEarnedLeaveAdditional(auth()->user()->id,$leave_balance->leave_type_info->id,$leave_balance->year);
                              $used_leave = checkUsedLeave(auth()->user()->id,$leave_balance->leave_type_info->id,$leave_balance->year);
                            }
                            
                            $total_balance = $leave_balance->total_balance + $additional_leave;
                            $remaining = $total_balance - $used_leave;
                            
                          @endphp

                          <tr>
                            <td>{{$leave_balance->leave_type}} {{$leave_balance->leave_type_info ? $leave_balance->leave_type_info->leave_type : "" }}</td>
                            <td>{{ round($total_balance) }}</td>
                            <td>{{ round($used_leave)}}</td>
                            <td>{{$remaining > 0 ? round($remaining) : 0}}</td>
                          <tr>
                        @endforeach
                      @endif

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
                    <h2 class="card-text">{{($employee_leaves_all->where('status','Pending'))->count()}}</h2>
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
                    <h4 class="mb-4">Declined/Cancelled</h4>
                    <h2 class="card-text">{{($employee_leaves_all->where('status','Cancelled'))->count() + ($employee_leaves_all->where('status','Declined'))->count()}}</h2>
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
                    <h2 class="card-text">{{($employee_leaves_all->where('status','Approved'))->count()}}</h2>
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

                <form method='get' onsubmit='show();' enctype="multipart/form-data">
                  <div class=row>
                    <div class='col-md-2'>
                      <div class="form-group">
                        <label class="text-right">From</label>
                        <input type="date" value='{{$from}}' class="form-control form-control-sm" name="from"
                            max='{{ date('Y-m-d') }}' onchange='get_min(this.value);' required />
                      </div>
                    </div>
                    <div class='col-md-2'>
                      <div class="form-group">
                        <label class="text-right">To</label>
                        <input type="date" value='{{$to}}' class="form-control form-control-sm" id='to' name="to" required />
                      </div>
                    </div>
                    <div class='col-md-2 mr-2'>
                      <div class="form-group">
                        <label class="text-right">Status</label>
                        <select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status' required>
                          <option value="">-- Select Status --</option>
                          <option value="Approved" @if ('Approved' == $status) selected @endif>Approved</option>
                          <option value="Pending" @if ('Pending' == $status) selected @endif>Pending</option>
                          <option value="Cancelled" @if ('Cancelled' == $status) selected @endif>Cancelled</option>
                          <option value="Declined" @if ('Declined' == $status) selected @endif>Declined</option>
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
                        <th>Date Filed</th> 
                        <th>Leave Date</th>
                        <th>Leave Type</th>
                        <th>With Pay </th>
                        <th>Half Day </th>
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
                        <td>{{date('M. d, Y h:i A', strtotime($employee_leave->created_at))}}</td>
                        <td>{{date('M. d, Y', strtotime($employee_leave->date_from))}} to {{date('M. d, Y', strtotime($employee_leave->date_to))}} </td>
                        <td>{{ $employee_leave->leave ? $employee_leave->leave->leave_type : "" }}</td>
                        @if($employee_leave->withpay == 1)   
                          <td>Yes</td>
                        @else
                          <td>No</td>
                        @endif  
                        @if($employee_leave->halfday == 1)   
                          <td>Yes</td>
                        @else
                          <td></td>
                        @endif  
                        <td>
                          <p title="{{ $employee_leave->reason }}" style="width: 250px;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                            {{ $employee_leave->reason }}
                          </p>
                        </td>
                        <td>{{get_count_days($employee_leave->schedule,$employee_leave->date_from,$employee_leave->date_to,$employee_leave->halfday)}}</td>
                        <td id="tdStatus{{ $employee_leave->id }}">
                          @if ($employee_leave->status == 'Pending')
                            <label class="badge badge-warning  mt-1">{{ $employee_leave->status }}</label>
                          @elseif($employee_leave->status == 'Approved')
                            <label class="badge badge-success mt-1">{{ $employee_leave->status }}</label>
                          @elseif($employee_leave->status == 'Rejected' || $employee_leave->status == 'Cancelled' || $employee_leave->status == 'Declined')
                            <label class="badge badge-danger  mt-1">{{ $employee_leave->status }}</label>
                          @endif                        
                        </td>
                        <td id="tdStatus{{ $employee_leave->id }}">
                          @if(count($employee_leave->approver) > 0)
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
                          @else
                            <label class="badge badge-danger mt-1">No Approver</label>
                          @endif
                        </td>
                        
                        <td id="tdActionId{{ $employee_leave->id }}" data-id="{{ $employee_leave->id }}">

                          @if ($employee_leave->status == 'Pending' && $employee_leave->level == 0)
                            <button type="button" id="view{{ $employee_leave->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_leave{{ $employee_leave->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>            
                            <a href="edit-leave/{{$employee_leave->id}}" class="btn btn-info btn-rounded btn-icon" title='Edit'>
                              <br>
                              <i class="ti-pencil-alt"></i>
                            </a>
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

                            @if(date('Y-m-d',strtotime($employee_leave->date_from)) == date('Y-m-d') || $employee_leave->withpay == 0)
                              @if($employee_leave->request_to_cancel == '1' || $employee_leave->request_to_cancel == null)
                                <button type="button" id="view{{ $employee_leave->id }}" class="btn btn-warning btn-rounded btn-icon"
                                  data-target="#requestToCancelLeave{{ $employee_leave->id }}" data-toggle="modal" title='Request to Cancel'>
                                  <i class="fa fa-ban"></i>
                                </button>  
                              @elseif($employee_leave->request_to_cancel == '0')
                                <button disabled type="button" id="view{{ $employee_leave->id }}" class="btn btn-warning btn-rounded btn-icon"
                                  data-target="#requestToCancelLeave{{ $employee_leave->id }}" data-toggle="modal" title='Request to Cancel has been Declined'>
                                  <i class="fa fa-ban"></i>
                                </button>  
                              @elseif($employee_leave->request_to_cancel == '2')
                                <button disabled type="button" id="view{{ $employee_leave->id }}" class="btn btn-warning btn-rounded btn-icon"
                                  data-target="#requestToCancelLeave{{ $employee_leave->id }}" data-toggle="modal" title='Request to Cancel has been Approved'>
                                  <i class="fa fa-ban"></i>
                                </button>  
                              @endif
                            @endif
                             
                            @if(date('Y-m-d',strtotime($employee_leave->date_from)) > date('Y-m-d'))
                                <button title='Cancel' id="{{ $employee_leave->id }}" onclick="cancel(this.id)"
                                  class="btn btn-rounded btn-danger btn-icon">
                                  <i class="fa fa-ban"></i>
                                </button> 
                            @endif
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
function get_count_days($data,$date_from,$date_to,$halfday)
 {

    if($date_from == $date_to){
        $count = 1;
    }else{
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
    }

    if($count == 1 && $halfday == 1){
      return '0.5';
    }else{
      return($count);
    }
    
 } 
@endphp  

@include('forms.leaves.apply_leave') 

@foreach ($employee_leaves as $leave)
  @include('forms.leaves.view_leave') 
  @include('forms.leaves.request_to_cancel') 
@endforeach




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

  <script>
    document.getElementById('leave_type').addEventListener('change', function() {
      var selectedOption = this.options[this.selectedIndex];
      // console.log(selectedOption)
      var balanceValue = selectedOption.getAttribute('data-balance');
      // alert(balanceValue)
      if (balanceValue !== null) {
        var checkbox = document.getElementById('withPayCheckBox');
        if(Number(balanceValue) > 0){
          checkbox.disabled = false;
          var leave_balances = document.getElementById('leave_balances');
          leave_balances.value = balanceValue;
        }else{
          checkbox.disabled = true;
          var leave_balances = document.getElementById('leave_balances');
          leave_balances.value = 0;
        }
      }
    });
  </script>

  <script>
    var halfdayCheck = document.getElementById('leaveHalfday');
    var dateTo = document.getElementById('dateToLeave');
    var halfday_status = document.getElementById('halfday_status');

    function updatehalfdayCheck() {
          if(halfdayCheck.checked) {
              dateTo.disabled = true;
              halfday_status.setAttribute('required', true); 
          } else {
              dateTo.disabled = false;
              halfday_status.removeAttribute('required');
          }
      }
      halfdayCheck.addEventListener('change', updatehalfdayCheck);
      window.onload = updatehalfdayCheck;
  </script>


@endsection
