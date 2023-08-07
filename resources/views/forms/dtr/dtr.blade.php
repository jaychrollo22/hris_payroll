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
                  <h2 class="card-text">{{($dtrs_all->where('status','Pending'))->count()}}</h2>
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
                  <h2 class="card-text">{{($dtrs_all->where('status','Cancelled'))->count() + ($dtrs_all->where('status','Declined'))->count()}}</h2>
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
                  <h2 class="card-text">{{($dtrs_all->where('status','Approved'))->count()}}</h2>
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
                <h4 class="card-title">DTR Correction</h4>
                <p class="card-description">
                  <button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#dtrc">
                    <i class="ti-plus btn-icon-prepend"></i>                                                    
                    Apply DTR Correction
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
                        <th>DTR Date </th>
                        <th>Correction</th>
                        <th>Time-in</th>
                        <th>Time-Out</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Approvers</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($dtrs as $dtr)
                      <tr>
                        <td> {{ date('M. d, Y h:i A', strtotime($dtr->created_at)) }}</td>
                        <td> {{ date('M. d, Y ', strtotime($dtr->dtr_date)) }}</td>
                        <td>{{ $dtr->correction }}</td>
                        <td> {{(isset($dtr->time_in)) ? date('M. d, Y h:i A', strtotime($dtr->time_in)) : '----'}}</td>
                        <td> {{(isset($dtr->time_out)) ? date('M. d, Y h:i A', strtotime($dtr->time_out)) : '----'}}</td>
                        <td>
                          <p title="{{ $dtr->remarks }}" style="width: 250px;white-space: nowrap; overflow: hidden;text-overflow: ellipsis;">
                            {{ $dtr->remarks }}
                          </p>
                        </td>
                        <td id="tdStatus{{ $dtr->id }}">
                          @if ($dtr->status == 'Pending')
                            <label class="badge badge-warning">{{ $dtr->status }}</label>
                          @elseif($dtr->status == 'Approved')
                            <label class="badge badge-success">{{ $dtr->status }}</label>
                          @elseif($dtr->status == 'Declined' or $dtr->status == 'Cancelled')
                            <label class="badge badge-danger">{{ $dtr->status }}</label>
                          @endif                        
                        </td>
                        <td id="tdStatus{{ $dtr->id }}">
                          @if(count($dtr->approver) > 0)
                            @foreach($dtr->approver as $approver)
                              @if($dtr->level >= $approver->level)
                                @if ($dtr->level == 0 && $dtr->status == 'Declined')
                                {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @else
                                  {{$approver->approver_info->name}} -  <label class="badge badge-success mt-1">Approved</label>
                                @endif
                              @else
                                @if ($dtr->status == 'Declined')
                                  {{$approver->approver_info->name}} -  <label class="badge badge-danger mt-1">Declined</label>
                                @else
                                  {{$approver->approver_info->name}} -  <label class="badge badge-warning mt-1">Pending</label>
                                @endif
                              @endif<br>
                            @endforeach
                          @else
                            <label class="badge badge-danger mt-1">No Approver</label>
                          @endif
                        </td>
                        <td id="tdActionId{{ $dtr->id }}" data-id="{{ $dtr->id }}">
                          @if ($dtr->status == 'Pending' and $dtr->level == 0)
                          <button type="button" id="view{{ $dtr->id }}" class="btn btn-primary btn-rounded btn-icon"
                            data-target="#view_dtr{{ $dtr->id }}" data-toggle="modal" title='View'>
                            <i class="ti-eye"></i>
                          </button>            
                            <button type="button" id="edit{{ $dtr->id }}" class="btn btn-info btn-rounded btn-icon"
                              data-target="#edit_dtr{{ $dtr->id }}" data-toggle="modal" title='Edit'>
                              <i class="ti-pencil-alt"></i>
                            </button>
                            <button title='Cancel' id="{{ $dtr->id }}" onclick="cancel(this.id)"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>
                          @elseif ($dtr->status == 'Pending' and $dtr->level > 0)
                            <button type="button" id="view{{ $dtr->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_dtr{{ $dtr->id }}" data-toggle="modal" title='View'>
                              <i class="ti-eye"></i>
                            </button>            
                              <button title='Cancel' id="{{ $dtr->id }}" onclick="cancel(this.id)"
                                class="btn btn-rounded btn-danger btn-icon">
                                <i class="fa fa-ban"></i>
                              </button>
                          @elseif ($dtr->status == 'Approved')   
                          <button type="button" id="view{{ $dtr->id }}" class="btn btn-primary btn-rounded btn-icon"
                            data-target="#view_dtr{{ $dtr->id }}" data-toggle="modal" title='View'>
                            <i class="ti-eye"></i>
                          </button>                            
                            <button title='Cancel' id="{{ $dtr->id }}" onclick="cancel(this.id)"
                              class="btn btn-rounded btn-danger btn-icon">
                              <i class="fa fa-ban"></i>
                            </button>  
                          @else
                            <button type="button" id="view{{ $dtr->id }}" class="btn btn-primary btn-rounded btn-icon"
                              data-target="#view_dtr{{ $dtr->id }}" data-toggle="modal" title='View'>
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
@foreach ($dtrs as $dtr)
  @include('forms.dtr.edit_dtr')
  @include('forms.dtr.view_dtr')
@endforeach  
@include('forms.dtr.apply_dtr') 
@endsection
@section('dtrScript')
	<script>
		function cancel(id) {
			var element = document.getElementById('tdActionId'+id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "You want to cancel this DTR Correction?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willCancel) => {
					if (willCancel) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "disable-dtr/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("DTR Correction has been cancelled!", {
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
            swal({text:"You stop the cancelation of DTR Correction.",icon:"success"});
					}
				});
		}

  function AdddtrType(sel) {

     if(sel.value === 'Both'){
        document.getElementById("time_in").required = true;
        document.getElementById("time_out").required = true;
        document.getElementById("time_in").disabled = false;
        document.getElementById("time_out").disabled = false;
     }else if(sel.value === 'Time-in'){
        document.getElementById("time_in").required = true;
        document.getElementById("time_out").required = false;
        document.getElementById("time_in").disabled = false;
        document.getElementById("time_out").disabled = true;
     }else{
        document.getElementById("time_in").required = false;
        document.getElementById("time_out").required = true;
        document.getElementById("time_in").disabled = true;
        document.getElementById("time_out").disabled = false;   
     }
  }

  function editDtr(sel,id) {
   var ti = document.getElementById("timein"+id);
   var to = document.getElementById("timeout"+id);
    
    if(sel.value === 'Both'){
      ti.required = true;
      to.required = true;
      ti.disabled = false;
      to.disabled = false;
      ti.value = ti.defaultValue;
      to.value = to.defaultValue;
     }else if(sel.value === 'Time-in'){
      ti.required = true;
      to.required = false;
      ti.disabled = false;
      to.disabled = true;
      to.value = '';
      ti.value = ti.defaultValue;
     }else{
      ti.required = false;
      to.required = true;
      ti.disabled = true;
      to.disabled = false;   
      ti.value = '';
      to.value = to.defaultValue;
     }
}

	</script>
@endsection