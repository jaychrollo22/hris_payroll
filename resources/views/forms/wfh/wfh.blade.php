@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
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
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>WFH Date</th>
                        <th>Date Filed</th>
                        <th>WFH Count (Days)</th>
                        <th>Remarks</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($wfhs as $wfh)
                      <tr>
                        <td> {{ date('M. d, Y ', strtotime($wfh->date_from)) }} - {{ date('M. d, Y ', strtotime($wfh->date_to)) }}  </td>
                        <td> {{ date('M. d, Y ', strtotime($wfh->created_at)) }}</td>
                        <td>{{get_count_days($wfh->schedule,$wfh->date_from,$wfh->date_to)}}</td>
                        <td>{{ $wfh->remarks }}</td>
                        <td id="tdStatus{{ $wfh->id }}">
                          @if ($wfh->status == 'Pending')
                            <label class="badge badge-warning">{{ $wfh->status }}</label>
                          @elseif($wfh->status == 'Approved')
                            <label class="badge badge-success">{{ $wfh->status }}</label>
                          @elseif($wfh->status == 'Rejected' or $wfh->status == 'Cancelled')
                            <label class="badge badge-danger">{{ $wfh->status }}</label>
                          @endif                        
                        </td>
                        <td id="tdActionId{{ $wfh->id }}" data-id="{{ $wfh->id }}">
                          @if ($wfh->status == 'Pending' and $wfh->level == 1)
                          <button type="button" id="view{{ $wfh->id }}" class="btn btn-primary btn-rounded btn-icon"
                            data-target="#view_wfh{{ $wfh->id }}" data-toggle="modal" title='View'>
                            <i class="ti-eye"></i>
                          </button>            
                            <button type="button" id="edit{{ $wfh->id }}" class="btn btn-info btn-rounded btn-icon"
                              data-target="#edit_wfh{{ $wfh->id }}" data-toggle="modal" title='Edit'>
                              <i class="ti-pencil-alt"></i>
                            </button>
                            <button title='Cancel' id="{{ $wfh->id }}" onclick="cancel(this.id)"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>
                          @elseif ($wfh->status == 'Pending' and $wfh->level > 1)
                            <button type="button" id="view{{ $wfh->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_wfh{{ $wfh->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>            
                              <button title='Cancel' id="{{ $wfh->id }}" onclick="cancel(this.id)"
                                class="btn btn-rounded btn-danger btn-icon">
                                <i class="fa fa-ban"></i>
                              </button>
                          @elseif ($wfh->status == 'Approved')   
                          <button type="button" id="view{{ $wfh->id }}" class="btn btn-primary btn-rounded btn-icon"
                            data-target="#view_wfh{{ $wfh->id }}" data-toggle="modal" title='View'>
                            <i class="ti-eye"></i>
                          </button>                            
                            <button title='Cancel' id="{{ $wfh->id }}" onclick="cancel(this.id)"
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
