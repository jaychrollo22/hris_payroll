@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Allowances</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newAllowance">
								<i class="ti-plus btn-icon-prepend"></i>
								New Allowance
							</button>
						</p>

						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>Allowance Name</th>
										<th>Date Created</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($allowance as $allowances)
										<tr>
											<td>{{ $allowances->name }}</td>
											<td> {{ date('M d Y ', strtotime($allowances->created_at)) }}</td>
											<td id="tdId{{ $allowances->id }}">
												@if ($allowances->status == 'Active')
													<label id="status{{ $allowances->id }}" class="badge badge-success">{{ $allowances->status }}</label>
												@else
													<label id="status{{ $allowances->id }}" class="badge badge-danger">{{ $allowances->status }}</label>
												@endif
											</td>
											<td id="tdActionId{{ $allowances->id }}" data-id="{{ $allowances->id }}">
												@if ($allowances->status == 'Active')
													<button type="button" id="edit{{ $allowances->id }}" class="btn btn-info btn-rounded btn-icon"
														data-target="#edit_allowance{{ $allowances->id }}" data-toggle="modal" title='Edit'>
														<i class="ti-pencil-alt"></i>
													</button>
													<button title='Disable' id="{{ $allowances->id }}" onclick="disable(this.id)"
														class="btn btn-rounded btn-danger btn-icon">
														<i class="fa fa-ban"></i>
													</button>
												@else
													<button title='Activate' id="{{ $allowances->id }}" onclick="activate(this.id)"
														class="btn btn-rounded btn-primary btn-icon">
														<i class="fa fa-check"></i>
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
	@include('allowances.edit_allowance')
	@include('allowances.new_allowance')
@endsection
@section('allowanceScript')
	<script>
		function disable(id) {
			var element = document.getElementById('tdActionId' + id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "Once deleted, you will not be able to recover this imaginary file!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDisable) => {
					if (willDisable) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "disable-allowance/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Allowance has been disable!", {
									icon: "success",
								}).then(function() {
									document.getElementById("tdId" + id).innerHTML =
										"<label class='badge badge-danger'>Inactive</label>";
									document.getElementById("tdActionId" + dataID).innerHTML =
										"<button title='Activate' id='action" + id +
										"' onclick='activate(" + id +
										")' class = 'btn btn-rounded btn-primary btn-icon' ><i class ='fa fa-check' > </i></button > "

								});
							}
						})

					} else {
						swal("Allowance is safe!");
					}
				});
		}

		function activate(id) {
			var element = document.getElementById('tdActionId' + id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "Once activated, you can edit and disable the allowance!",
					icon: "info",
					buttons: true,
					dangerMode: true,
				})
				.then((willActivate) => {
					if (willActivate) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "activate-allowance/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Allowance has been activated!", {
									icon: "success",
								}).then(function() {
									document.getElementById("tdId" + id).innerHTML =
										"<label class='badge badge-success'>Active</label>";
									document.getElementById("tdActionId" + dataID).innerHTML =
										"<button type='button' id='edit" + id +
										"' class='btn btn-info btn-rounded btn-icon' data-target='#edit_allowance" +
										id +
										"' data-toggle='modal' title='Edit'>	<i class='ti-pencil-alt'></i></button> <button title='Disable' id='action" +
										id + "' onclick = 'disable(" + id +
										")' class = 'btn btn-rounded btn-danger btn-icon'><i class='fa fa-ban'></i></button > ";
									// document.getElementById(id).remove();
								});
							}
						})

					} else {
						swal("Allowance is safe!");
					}
				});
		}
	</script>
@endsection
