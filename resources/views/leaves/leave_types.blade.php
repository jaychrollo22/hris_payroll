@extends('layouts.header')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Leave Types</h4>
                <p class="card-description">
                    @if (checkUserPrivilege('settings_add',auth()->user()->id) == 'yes')
                    <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newLeaveType">
                      <i class="ti-plus btn-icon-prepend"></i>                                                    
                      New Leave Type
                    </button>
                    @endif
                  </p>
             
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Leave Type</th>
                            <th>Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leave_types as $leave)
                            <tr >
                                <td>{{$leave->id}}</td>
                                <td>{{$leave->leave_type}}</td>
                                <td>{{$leave->code}}</td>
                                <td id="tdActionId{{ $leave->id }}" data-id="{{ $leave->id }}">
                                  @if (checkUserPrivilege('settings_edit',auth()->user()->id) == 'yes')
                                    <button type="button" class="btn btn-info btn-rounded btn-icon" href="#edit_leave_type{{$leave->id}}" data-toggle="modal" title='EDIT'>
                                        <i class="ti-pencil-alt"></i>
                                    </button>
                                    @if($leave->code == 'VL' || $leave->code == 'SL')
                                      <button title='Delete' disabled
                                        class="btn btn-rounded btn-danger btn-icon">
                                        <i class="fa fa-ban"></i>
                                      </button>
                                    @else
                                      <button title='Delete' id="{{ $leave->id }}" onclick="cancel(this.id)"
                                        class="btn btn-rounded btn-danger btn-icon">
                                        <i class="fa fa-ban"></i>
                                      </button>
                                    @endif
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

@include('leaves.new_leave_type')

@foreach($leave_types as $leave)
@include('leaves.edit_leave_type')
@endforeach

@endsection

@section('ForApprovalScript')
	<script>
		function cancel(id) {
			var element = document.getElementById('tdActionId'+id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "You want to delete this leave?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willCancel) => {
					if (willCancel) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "delete-leave-type/" + id,
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
									location.reload();
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
