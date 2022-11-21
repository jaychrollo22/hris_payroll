@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
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
                <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                      <tr>
                        <th>Date Filed</th>
                        <th>OB Date</th>
                        <th>Destination</th>
                        <th>Person/Company to see</th>
                        <th>Purpose</th>
                        <th>Status </th>
                        <th>Action </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($obs as $ob)
                      <tr>
                        <td> {{ date('M. d, Y ', strtotime($ob->created_at)) }} </td>
                        <td> {{ date('M. d, Y ', strtotime($ob->date_from)) }} - {{ date('M. d, Y ', strtotime($ob->date_to)) }}  </td>
                        <td> {{$ob->destination}}</td>
                        <td> {{$ob->persontosee}}</td>
                        <td> {{$ob->remarks}}</td>
                        <td id="tdStatus{{ $ob->id }}">
                          @if ($ob->status == 'Pending')
                            <label class="badge badge-warning">{{ $ob->status }}</label>
                          @elseif($ob->status == 'Approved')
                            <label class="badge badge-success">{{ $ob->status }}</label>
                          @elseif($ob->status == 'Rejected' or $ob->status == 'Cancelled')
                            <label class="badge badge-danger">{{ $ob->status }}</label>
                          @endif                        
                        </td>
                        <td id="tdActionId{{ $ob->id }}" data-id="{{ $ob->id }}">
                          @if ($ob->status == 'Pending' and $ob->level == 1)
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
                          @elseif ($ob->status == 'Pending' and $ob->level > 1)
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
            @include('forms.officialbusiness.apply_ob')
          </div>
        </div>
    </div>
</div>
@foreach ($obs as $ob)
  @include('forms.officialbusiness.edit_ob')
@endforeach  
@foreach ($obs as $ob)
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
