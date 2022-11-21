@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
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
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($dtrs as $dtr)
                      <tr>
                        <td> {{ date('M. d, Y ', strtotime($dtr->created_at)) }}</td>
                        <td> {{ date('M. d, Y ', strtotime($dtr->dtr_date)) }}</td>
                        <td>{{ $dtr->correction }}</td>
                        <td> {{(isset($dtr->time_in)) ? date('h:i A', strtotime($dtr->time_in)) : '----'}}</td>
                        <td> {{(isset($dtr->time_out)) ? date('h:i A', strtotime($dtr->time_out)) : '----'}}</td>
                        <td>{{ $dtr->remarks }}</td>
                        <td id="tdStatus{{ $dtr->id }}">
                          @if ($dtr->status == 'Pending')
                            <label class="badge badge-warning">{{ $dtr->status }}</label>
                          @elseif($dtr->status == 'Approved')
                            <label class="badge badge-success">{{ $dtr->status }}</label>
                          @elseif($dtr->status == 'Rejected' or $dtr->status == 'Cancelled')
                            <label class="badge badge-danger">{{ $dtr->status }}</label>
                          @endif                        
                        </td>
                        <td id="tdActionId{{ $dtr->id }}" data-id="{{ $dtr->id }}">
                          @if ($dtr->status == 'Pending' and $dtr->level == 1)
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
                          @elseif ($dtr->status == 'Pending' and $dtr->level > 1)
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
@endforeach  
@foreach ($dtrs as $dtr)
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