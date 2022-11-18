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
                    <h4 class="mb-4">Pending</h4>
                    <h2 class="card-text">{{($overtimes->where('status','Pending'))->count()}}</h2>
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
                    <h2 class="card-text">{{($overtimes->where('status','Cancelled'))->count()}}</h2>
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
                    <h2 class="card-text">{{($overtimes->where('status','Approved'))->count()}}</h2>
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
                <h4 class="card-title">Overtime</h4>
                <p class="card-description">
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#applyovertime">
                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                    Apply Overtime
                  </button>
                </p>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>Date Filed</th>
                        <th>OT Date</th> 
                        <th>OT Time</th> 
                        <th>OT Requested (Hrs)</th>
                        <th>OT Approved (Hrs)</th>
                        <th>OT Rendered (Hrs)</th>
                        <th>Remarks </th>
                        <th>Status </th>
                        <th>Action </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($overtimes as $overtime)
                      <tr>
                        <td> {{ date('M. d, Y ', strtotime($overtime->created_at)) }}</td>
                        <td> {{ date('M. d, Y ', strtotime($overtime->ot_date)) }}</td>
                        <td> {{ date('h:i A', strtotime($overtime->start_time)) }} - {{ date('h:i A', strtotime($overtime->end_time)) }}</td>
                        <td> {{intval((strtotime($overtime->end_time)-strtotime($overtime->start_time))/60/60)}}</td>
                        <td> 0</td>
                        <td> 0</td>
                        <td>{{ $overtime->remarks }}</td>
                        <td id="tdStatus{{ $overtime->id }}">
                          @if ($overtime->status == 'Pending')
                            <label class="badge badge-warning">{{ $overtime->status }}</label>
                          @elseif($overtime->status == 'Approved')
                            <label class="badge badge-success">{{ $overtime->status }}</label>
                          @elseif($overtime->status == 'Rejected' or $overtime->status == 'Cancelled')
                            <label class="badge badge-danger">{{ $overtime->status }}</label>
                          @endif                        
                        </td>
                        <td id="tdActionId{{ $overtime->id }}" data-id="{{ $overtime->id }}">
                          @if ($overtime->status == 'Pending' and $overtime->level == 1)
                          <button type="button" id="view{{ $overtime->id }}" class="btn btn-primary btn-rounded btn-icon"
                            data-target="#view_overtime{{ $overtime->id }}" data-toggle="modal" title='View'>
                            <i class="ti-eye"></i>
                          </button>            
                            <button type="button" id="edit{{ $overtime->id }}" class="btn btn-info btn-rounded btn-icon"
                              data-target="#edit_overtime{{ $overtime->id }}" data-toggle="modal" title='Edit'>
                              <i class="ti-pencil-alt"></i>
                            </button>
                            <button title='Cancel' id="{{ $overtime->id }}" onclick="cancel(this.id)"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>
                          @elseif ($overtime->status == 'Pending' and $overtime->level > 1)
                            <button type="button" id="view{{ $overtime->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_overtime{{ $overtime->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>            
                              <button title='Cancel' id="{{ $overtime->id }}" onclick="cancel(this.id)"
                                class="btn btn-rounded btn-danger btn-icon">
                                <i class="fa fa-ban"></i>
                              </button>
                          @elseif ($overtime->status == 'Approved')   
                          <button type="button" id="view{{ $overtime->id }}" class="btn btn-primary btn-rounded btn-icon"
                            data-target="#view_overtime{{ $overtime->id }}" data-toggle="modal" title='View'>
                            <i class="ti-eye"></i>
                          </button>                            
                            <button title='Cancel' id="{{ $overtime->id }}" onclick="cancel(this.id)"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>  
                          @else
                            <button type="button" id="view{{ $overtime->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_overtime{{ $overtime->id }}" data-toggle="modal" title='View'>
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
@foreach ($overtimes as $overtime)
  @include('forms.overtime.edit_overtime')
@endforeach  
@foreach ($overtimes as $overtime)
  @include('forms.overtime.view_overtime')
@endforeach  
 @include('forms.overtime.apply_overtime') 
@endsection
@section('OvertimeScript')
	<script>
		function cancel(id) {
			var element = document.getElementById('tdActionId'+id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "You want to cancel this overtime?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willCancel) => {
					if (willCancel) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "disable-overtime/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Overtime has been cancelled!", {
									icon: "success",
								}).then(function() {
									document.getElementById("tdStatus" + id).innerHTML =
										"<label class='badge badge-danger'>Cancelled</label>";
                  document.getElementById("edit" + dataID).remove();
                  document.getElementById(dataID).remove();
								});
							}
						})

					} else {
            swal({text:"You stop the cancelation of overtime.",icon:"success"});
					}
				});
		}

	</script>
@endsection

