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
                    <h2 class="card-text">{{($obs_all->where('status','Pending'))->count()}}</h2>
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
                    <h2 class="card-text">{{($obs_all->where('status','Cancelled'))->count() + ($obs_all->where('status','Declined'))->count()}}</h2>
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
                    <h2 class="card-text">{{($obs_all->where('status','Approved'))->count()}}</h2>
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
                <h4 class="card-title">Official Business</h4>
                <p class="card-description">
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#ob">
                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                    Apply Official Business
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
                        <th>Date</th>
                        <th>Time In-Out</th>
                        <th>Destination</th>
                        <th>Person/Company to see</th>
                        <th>Purpose</th>
                        <th>Status </th>
                        <th>Approvers </th>
                        <th>Action </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($obs as $ob)
                      <tr>
                        <td> {{ date('M. d, Y', strtotime($ob->created_at)) }} </td>
                        <td> {{ date('M. d, Y', strtotime($ob->applied_date)) }} </td>
                        <td> {{ date('M. d, Y h:i A', strtotime($ob->date_from)) }} - {{ date('M. d, Y h:i A', strtotime($ob->date_to)) }}  </td>
                        <td> {{$ob->destination}}</td>
                        <td> {{$ob->persontosee}}</td>
                        <td> 
                          <p title="{{ $ob->remarks }}" style="width: 250px;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                            {{$ob->remarks}}
                          </p>
                        </td>
                        <td id="tdStatus{{ $ob->id }}">
                          @if ($ob->status == 'Pending')
                            <label class="badge badge-warning">{{ $ob->status }}</label>
                          @elseif($ob->status == 'Approved')
                            <label class="badge badge-success">{{ $ob->status }}</label>
                          @elseif($ob->status == 'Rejected' or $ob->status == 'Cancelled')
                            <label class="badge badge-danger">{{ $ob->status }}</label>
                          @endif                        
                        </td>
                        <td id="tdStatus{{ $ob->id }}">
                          @if(count($ob->approver) > 0)
                            @foreach($ob->approver as $approver)
                              @if($ob->level >= $approver->level)
                                @if ($ob->level == 0 && $ob->status == 'Declined')
                                  {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @elseif ($ob->level == 1 && $ob->status == 'Declined')
                                  {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @else
                                  {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                @endif
                              @else
                                @if ($ob->status == 'Declined')
                                  {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @elseif ($ob->status == 'Approved')
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
                        
                        <td id="tdActionId{{ $ob->id }}" data-id="{{ $ob->id }}">
                          @if ($ob->status == 'Pending' and $ob->level == 0)
                          <button type="button" id="view{{ $ob->id }}" class="btn btn-primary btn-rounded btn-icon"
                            data-target="#view_ob{{ $ob->id }}" data-toggle="modal" title='View'>
                            <i class="ti-eye"></i>
                          </button>            
                            <button type="button" id="edit{{ $ob->id }}" class="btn btn-info btn-rounded btn-icon"
                              data-target="#edit_ob{{ $ob->id }}" data-toggle="modal" title='Edit'>
                              <i class="ti-pencil-alt"></i>
                            </button>
                            <button title='Cancel' id="{{ $ob->id }}" onclick="cancel(this.id)"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>
                          @elseif ($ob->status == 'Pending' and $ob->level > 0)
                            <button type="button" id="view{{ $ob->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_ob{{ $ob->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>            
                              <button title='Cancel' id="{{ $ob->id }}" onclick="cancel(this.id)"
                                class="btn btn-rounded btn-danger btn-icon">
                                <i class="fa fa-ban"></i>
                              </button>
                          @elseif ($ob->status == 'Approved')   
                          <button type="button" id="view{{ $ob->id }}" class="btn btn-primary btn-rounded btn-icon"
                            data-target="#view_ob{{ $ob->id }}" data-toggle="modal" title='View'>
                            <i class="ti-eye"></i>
                          </button>                            
                            <button title='Cancel' id="{{ $ob->id }}" onclick="cancel(this.id)"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>  
                          @else
                            <button type="button" id="view{{ $ob->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_ob{{ $ob->id }}" data-toggle="modal" title='View'>
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

@foreach ($obs as $ob)
  @include('forms.officialbusiness.edit_ob')
  @include('forms.officialbusiness.view_ob')
@endforeach  

@include('forms.officialbusiness.apply_ob') 

@endsection
@section('obScript')
	<script>
		function cancel(id) {
			var element = document.getElementById('tdActionId'+id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "You want to cancel this official business?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willCancel) => {
					if (willCancel) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "disable-ob/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Official Business has been cancelled!", {
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
            swal({text:"You stop the cancelation of official Business.",icon:"success"});
					}
				});
		}

	</script>
@endsection
