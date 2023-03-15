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
                    <a href="/for-official-business?status=Pending" class="h2 card-text text-white">{{$for_approval}}</a>
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
                    <a href="/for-official-business?status=Approved" class="h2 card-text text-white">{{$approved}}</a>
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
                        <td>{{$form_approval->user->name}}</td>
                        <td> {{ date('d/m/Y', strtotime($form_approval->created_at)) }} </td>
                        <td> {{ date('d/m/Y ', strtotime($form_approval->applied_date)) }}</td>
                        <td> {{ date('H:i', strtotime($form_approval->date_from)) }} - {{ date('H:i', strtotime($form_approval->date_to)) }}  </td>
                        <td> {{$form_approval->destination}}</td>
                        <td> {{$form_approval->persontosee}}</td>
                        <td> {{$form_approval->remarks}}</td>
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
                            <label class="badge badge-success">{{ $form_approval->status }}</label>
                          @elseif($form_approval->status == 'Declined' || $form_approval->status == 'Cancelled')
                            <label class="badge badge-danger">{{ $form_approval->status }}</label>
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
@section('ForApprovalScript')
	<script>
		function approve(id) {
			var element = document.getElementById('tdActionId'+id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "You want to approve this OB?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willApprove) => {
					if (willApprove) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "approve-ob/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("OB has been Approved!", {
									icon: "success",
								}).then(function() {
									location.reload();
								});
							}
						})

					} else {
                        swal({text:"You stop the approval of OB.",icon:"success"});
					}
				});
		}
		function decline(id) {
			var element = document.getElementById('tdActionId'+id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "You want to decline this OB?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDecline) => {
					if (willDecline) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "decline-ob/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Wfh has been declined!", {
									icon: "success",
								}).then(function() {
									location.reload();
								});
							}
						})

					} else {
                        swal({text:"You stop the approval of OB.",icon:"success"});
					}
				});
		}

	</script>
@endsection

