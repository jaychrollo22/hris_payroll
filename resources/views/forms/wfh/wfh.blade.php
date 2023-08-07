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
                    <h2 class="card-text">{{($wfhs_all->where('status','Pending'))->count()}}</h2>
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
                    <h2 class="card-text">{{($wfhs_all->where('status','Cancelled'))->count() + ($wfhs_all->where('status','Declined'))->count()}}</h2>
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
                    <h2 class="card-text">{{($wfhs_all->where('status','Approved'))->count()}}</h2>
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
                <h4 class="card-title">Work from Home</h4>
                <p class="card-description">
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#wfh">
                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                    Apply Work from Home
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
                        <th>WFH Date</th>
                        <th>WFH Time In-Out</th>
                        {{-- <th>WFH Count (Days)</th> --}}
                        <th>Remarks</th>
                        <th>Status</th>
                        <th>Approvers</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($wfhs as $wfh)
                      <tr>
                        <td> {{ date('M. d, Y h:i A', strtotime($wfh->created_at)) }}</td>
                        <td> {{ date('M. d, Y', strtotime($wfh->applied_date)) }} </td>
                        <td> {{ date('h:i A', strtotime($wfh->date_from)) }} - {{ date('h:i A', strtotime($wfh->date_to)) }}  </td>
                        {{-- <td>{{get_count_days($wfh->schedule,$wfh->date_from,$wfh->date_to)}}</td> --}}
                        <td>
                          <p title="{{ $wfh->remarks }}" style="width: 250px;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                            {{ $wfh->remarks }}
                          </p>
                        </td>
                        <td id="tdStatus{{ $wfh->id }}">
                          @if ($wfh->status == 'Pending')
                            <label class="badge badge-warning">{{ $wfh->status }}</label>
                          @elseif($wfh->status == 'Approved')
                            <label class="badge badge-success">{{ $wfh->status }}</label>
                          @elseif($wfh->status == 'Declined' or $wfh->status == 'Cancelled')
                            <label class="badge badge-danger">{{ $wfh->status }}</label>
                          @endif                        
                        </td>
                        <td id="tdStatus{{ $wfh->id }}">
                          @if(count($wfh->approver) > 0)
                            @foreach($wfh->approver as $approver)
                              @if($wfh->level >= $approver->level)
                                @if ($wfh->level == 0 && $wfh->status == 'Declined')
                                  {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @elseif ($wfh->level == 1 && $wfh->status == 'Declined')
                                  {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @else
                                  {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                @endif
                              @else
                                @if ($wfh->status == 'Declined')
                                  {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @elseif ($wfh->status == 'Approved')
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
                        <td id="tdActionId{{ $wfh->id }}" data-id="{{ $wfh->id }}">
                          @if ($wfh->status == 'Pending' and $wfh->level == 0)
                          <button type="button" id="view{{ $wfh->id }}" class="btn btn-primary btn-rounded btn-icon"
                            data-target="#view_wfh{{ $wfh->id }}" data-toggle="modal" title='View'>
                            <i class="ti-eye"></i>
                          </button>            
                            <button type="button" id="edit{{ $wfh->id }}" class="btn btn-info btn-rounded btn-icon"
                              data-target="#edit_wfh{{ $wfh->id }}" data-toggle="modal" title='Edit'>
                              <i class="ti-pencil-alt"></i>
                            </button>
                            <button title='Cancel' id="{{ $wfh->id }}" onclick="cancel({{$wfh->id}})"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>
                          @elseif ($wfh->status == 'Pending' and $wfh->level > 1)
                            <button type="button" id="view{{ $wfh->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_wfh{{ $wfh->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>            
                            <button title='Cancel' id="{{ $wfh->id }}" onclick="cancel({{$wfh->id}})"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>
                          @elseif ($wfh->status == 'Approved')   
                            <button type="button" id="view{{ $wfh->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_wfh{{ $wfh->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>                 
                            <button title='Cancel' id="{{ $wfh->id }}" onclick="cancel({{$wfh->id}})"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>            
                          @else
                            <button type="button" id="view{{ $wfh->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_wfh{{ $wfh->id }}" data-toggle="modal" title='View'>
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
@foreach ($wfhs as $wfh)
  @include('forms.wfh.edit_wfh')
@endforeach  
@foreach ($wfhs as $wfh)
  @include('forms.wfh.view_wfh')
@endforeach  
 @include('forms.wfh.apply_wfh') 
@endsection
@section('wfhScript')
	<script>
		function cancel(id) {
      alert(id);
			var element = document.getElementById('tdActionId'+id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "You want to cancel this work from home?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willCancel) => {
					if (willCancel) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "disable-wfh/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Work from home has been cancelled!", {
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
            swal({text:"You stop the cancelation of work from home.",icon:"success"});
					}
				});
		}

	</script>
@endsection
