@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Departments</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal"
								data-target="#newDepartment">
								<i class="ti-plus btn-icon-prepend"></i>
								New Department
							</button>
						</p>

						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>Department Name</th>
										<th>Department Code</th>
										<th>Date Created</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($departments as $department)
										<tr>
											<td>{{ $department->name }}</td>
											<td>{{ $department->code }}</td>
											<td>{{ $department->created_at }}</td>
											<td id="tdId{{ $department->id }}">
												@if ($department->status == 1)
													<label id="status{{ $department->id }}" class="badge badge-success">Active</label>
												@else
													<label id="status{{ $department->id }}" class="badge badge-danger">Inactive</label>
												@endif
											</td>
											<td id="tdActionId{{ $department->id }}" data-id="{{ $department->id }}">
												@if ($department->status == 1)
													<button title='Disable' id="{{ $department->id }}" onclick="disableDept(this.id)"
														class="btn btn-rounded btn-danger btn-icon">
														<i class="fa fa-ban"></i>
													</button>
												@else
													<button title='Activate' id="{{ $department->id }}" onclick="activateDept(this.id)"
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
	@include('masterfiles.new_department')
@endsection
@section('masterfilesScript')
	<script>
		function disableDept(id) {
			var element = document.getElementById('tdActionId' + id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "Once disable, you will not be able to recover this imaginary file!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDisable) => {
					if (willDisable) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "disable-department/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Department has been disable!", {
									icon: "success",
								}).then(function() {
									document.getElementById("tdId" + id).innerHTML =
										"<label class='badge badge-danger'>Inactive</label>";
									document.getElementById("tdActionId" + dataID).innerHTML =
										"<button title='Activate' id='action" + id +
										"' onclick='activateDept(" + id +
										")' class = 'btn btn-rounded btn-primary btn-icon' ><i class ='fa fa-check' > </i></button > "

								});
							}
						})

					} else {
						swal("Department is safe!");
					}
				});
		}

		function activateDept(id) {
			var element = document.getElementById('tdActionId' + id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "Once activated, you can disable the department!",
					icon: "info",
					buttons: true,
					dangerMode: true,
				})
				.then((willActivate) => {
					if (willActivate) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "enable-department/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Department has been activated!", {
									icon: "success",
								}).then(function() {
									document.getElementById("tdId" + id).innerHTML =
										"<label class='badge badge-success'>Active</label>";
									document.getElementById("tdActionId" + dataID).innerHTML =
										"<button title='Disable' id='action" +
										id + "' onclick = 'disableDept(" + id +
										")' class = 'btn btn-rounded btn-danger btn-icon'><i class='fa fa-ban'></i></button > ";
									// document.getElementById(id).remove();
								});
							}
						})

					} else {
						swal("Department is safe!");
					}
				});
		}
	</script>
@endsection
