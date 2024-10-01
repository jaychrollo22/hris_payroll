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

						<h4 class="card-title">Filter</h4>
						<p class="card-description">
							<form method='get' onsubmit='show();' enctype="multipart/form-data">
								<div class=row>
									<div class='col-md-2 mr-2'>
										<div class="form-group">
											<label class="text-right">Status</label>
											<select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='status' required>
												<option value="">-- Select Status --</option>
												<option value="Active" @if ('Active' == $status) selected @endif>Active</option>
												<option value="Inactive" @if ('Inactive' == $status) selected @endif>Inactive</option>
											</select>
										</div>
									</div>
									<div class='col-md-2'>
										<button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Generate</button>
									</div>
								</div>
								
							</form>
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
									@foreach ($allowances as $allowance)
										<tr>
											<td>{{ $allowance->name }}</td>
											<td> {{ date('M d Y ', strtotime($allowance->created_at)) }}</td>
											<td id="tdId{{ $allowance->id }}">
												@if ($allowance->status == 'Active')
													<label id="status{{ $allowance->id }}" class="badge badge-success">{{ $allowance->status }}</label>
												@else
													<label id="status{{ $allowance->id }}" class="badge badge-danger">{{ $allowance->status }}</label>
												@endif
											</td>
											<td id="tdActionId{{ $allowance->id }}" data-id="{{ $allowance->id }}">
												@if ($allowance->status == 'Active')
													<button type="button" id="edit{{ $allowance->id }}" class="btn btn-info btn-rounded btn-icon"
														data-target="#edit_allowance{{ $allowance->id }}" data-toggle="modal" title='Edit'>
														<i class="ti-pencil-alt"></i>
													</button>
													<button title='Disable' id="{{ $allowance->id }}" onclick="disable(this.id)"
														class="btn btn-rounded btn-danger btn-icon">
														<i class="fa fa-ban"></i>
													</button>
												@else
													<button title='Activate' id="{{ $allowance->id }}" onclick="activate(this.id)"
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
	@foreach ($allowances as $allowance)
		@include('allowances.edit_allowance')
	@endforeach
	@include('allowances.new_allowance')
@endsection
@section('allowanceScript')
	<script>
		function disable(id) {
			var element = document.getElementById('tdActionId' + id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "Once disabled, you will not be able to recover this imaginary file!",
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
										"' data-toggle='modal' title='Disable'>	<i class='ti-pencil-alt'></i></button> <button title='Disable' id='action" +
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
