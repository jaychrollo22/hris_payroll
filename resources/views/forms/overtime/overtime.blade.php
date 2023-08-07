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
                    <h2 class="card-text">{{($overtimes_all->where('status','Pending'))->count()}}</h2>
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
                    <h2 class="card-text">{{($overtimes_all->where('status','Cancelled'))->count() + ($overtimes_all->where('status','Declined'))->count()}}</h2>
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
                    <h2 class="card-text">{{($overtimes_all->where('status','Approved'))->count()}}</h2>
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
                        <th>OT Date</th> 
                        <th>OT Time</th> 
                        <th>OT Requested (Hrs)</th>
                        <th>Break (Hrs)</th>
                        <th>OT Approved (Hrs)</th>
                        <th>Total OT Approved (Hrs)</th>
                        <th>Remarks </th>
                        <th>Status </th>
                        <th>Approvers </th>
                        <th>Action </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($overtimes as $overtime)
                      <tr>
                        <td> {{ date('M. d, Y h:i A', strtotime($overtime->created_at)) }}</td>
                        <td> {{ date('M. d, Y ', strtotime($overtime->ot_date)) }}</td>
                        <td> {{ date('M. d, Y h:i A', strtotime($overtime->start_time)) }} - {{ date('M. d, Y h:i A', strtotime($overtime->end_time)) }}</td>
                        <td> 
                          @php
                            $startTime = new DateTime($overtime->start_time);
                            $endTime = new DateTime($overtime->end_time);

                            // Calculate the time difference
                            $timeDifference = $endTime->diff($startTime);
                            // Convert the time difference to decimal hours
                            $total = ($timeDifference->days * 24) + $timeDifference->h + ($timeDifference->i / 60);
                          @endphp
                          {{ number_format($total,2)}}
                        </td>
                        <td> {{$overtime->break_hrs}}</td>
                        <td> {{$overtime->ot_approved_hrs}}</td>
                        <td>{{$overtime->ot_approved_hrs - $overtime->break_hrs}}</td>
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
                        <td>
                          @if(count($overtime->approver) > 0)
                            @foreach($overtime->approver as $approver)
                              @if($overtime->level >= $approver->level)
                                @if ($overtime->level == 0 && $overtime->status == 'Declined')
                                {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @elseif ($overtime->level == 1 && $overtime->status == 'Declined')
                                {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @else
                                  {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                @endif
                              @else
                                @if ($overtime->status == 'Declined')
                                  {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @elseif ($overtime->status == 'Approved')
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

                        <td id="tdActionId{{ $overtime->id }}" data-id="{{ $overtime->id }}">
                          @if ($overtime->status == 'Pending' and $overtime->level == 0)
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
                          @elseif ($overtime->status == 'Pending' && $overtime->level > 1)
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

