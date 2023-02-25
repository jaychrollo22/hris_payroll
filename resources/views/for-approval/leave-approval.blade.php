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
                    <h4 class="mb-4">For Appoval</h4>
                    <a href="/for-leave?status=Pending" class="h2 card-text text-white">{{$for_approval}}</a>
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
                    <h4 class="mb-4">Approved</h4>
                    <a href="/for-leave?status=Approved" class="h2 card-text text-white">{{$approved}}</a>
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
                    <h4 class="mb-4">Declined / Rejected</h4>
                    <a href="/for-leave?status=Declined" class="h2 card-text text-white">{{$declined}}</a>
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
                <h4 class="card-title">For Approval Leave</h4>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>Employee Name</th>
                        <th>Form Type</th>
                        <th>Date</th>
                        <th>Status</th> 
                        <th>Approvers</th> 
                        <th>Reason/Remarks</th> 
                        <th>Attachment</th>
                        <th>Action </th> 
                      </tr>
                    </thead>
                    <tbody> 
                      @foreach ($leaves as $form_approval)
                      <tr>
                        <td>{{$form_approval->user->name}}</td>
                        <td>{{$form_approval->leave->leave_type}}</td>
                        <td>{{date('M d, Y', strtotime($form_approval->date_from))}} - {{date('M d, Y', strtotime($form_approval->date_to))}}</td>
                        <td>
                          @if ($form_approval->status == 'Pending')
                            <label class="badge badge-warning">{{ $form_approval->status }}</label>
                          @elseif($form_approval->status == 'Approved')
                            <label class="badge badge-success">{{ $form_approval->status }}</label>
                          @elseif($form_approval->status == 'Rejected' || $form_approval->status == 'Cancelled')
                            <label class="badge badge-danger">{{ $form_approval->status }}</label>
                          @endif  
                        </td>
                        <td id="tdStatus{{ $form_approval->id }}">
                          @foreach($form_approval->approver as $approver)
                            @if($form_approval->level >= $approver->level)
                            {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                            @else
                            {{$approver->approver_info->name}} -  <label class="badge badge-warning mt-1">Pending</label>
                            @endif<br> 
                          @endforeach
                        </td>
                        <td>{{$form_approval->reason}}</td>
                        <td>
                          @if($form_approval->attachment)
                          <a href="{{url($form_approval->attachment)}}" target='_blank' class="text-start"><button type="button" class="btn btn-outline-info btn-sm ">View Attachment</button></a>
                          @endif
                        </td>
                        <td align="center" id="tdActionId{{ $form_approval->id }}" data-id="{{ $form_approval->id }}">

                          @foreach($form_approval->approver as $approver)
                            @if($approver->approver_id == $approver_id && $form_approval->level < $approver->level && $form_approval->status == 'Pending')
                              <button type="button" class="btn btn-success btn-sm" id="{{ $form_approval->id }}" onclick="approve({{ $form_approval->id }})">
                                <i class="ti-check btn-icon-prepend"></i>                                                    
                              </button>
                              <button type="button" class="btn btn-danger btn-sm" id="{{ $form_approval->id }}" onclick="decline({{ $form_approval->id }})">
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
@endsection

@section('LeaveScript')
	<script>
		function approve(id) {
			var element = document.getElementById('tdActionId'+id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "You want to approve this leave?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willApprove) => {
					if (willApprove) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "approve-leave/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Leave has been Approved!", {
									icon: "success",
								}).then(function() {
									location.reload();
								});
							}
						})

					} else {
            swal({text:"You stop the approval of leave.",icon:"success"});
					}
				});
		}
		function decline(id) {
			var element = document.getElementById('tdActionId'+id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "You want to decline this leave?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDecline) => {
					if (willDecline) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "decline-leave/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Leave has been declined!", {
									icon: "success",
								}).then(function() {
									location.reload();
								});
							}
						})

					} else {
            swal({text:"You stop the approval of leave.",icon:"success"});
					}
				});
		}

	</script>
@endsection

