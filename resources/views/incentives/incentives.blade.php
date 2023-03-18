@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Incentives</h4>
						<p class="card-description">
							@if (checkUserPrivilege('settings_add',auth()->user()->id) == 'yes')
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newIncentive">
								<i class="ti-plus btn-icon-prepend"></i>
								New Incentive
							</button>
							@endif
						</p>

						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>Incentive Name</th>
										<th>Date Created</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($incentives as $incentive)
										<tr>
											<td>{{ $incentive->name }}</td>
											<td> {{ date('M d Y ', strtotime($incentive->created_at)) }}</td>
											<td id="tdId{{ $incentive->id }}">
												@if ($incentive->status == 'Active')
													<label id="status{{ $incentive->id }}" class="badge badge-success">{{ $incentive->status }}</label>
												@else
													<label id="status{{ $incentive->id }}" class="badge badge-danger">{{ $incentive->status }}</label>
												@endif
											</td>
											<td id="tdActionId{{ $incentive->id }}" data-id="{{ $incentive->id }}">
												@if ($incentive->status == 'Active')
													@if (checkUserPrivilege('settings_edit',auth()->user()->id) == 'yes')
													<button type="button" id="edit{{ $incentive->id }}" class="btn btn-info btn-rounded btn-icon"
														data-target="#edit_incentive{{ $incentive->id }}" data-toggle="modal" title='Edit'>
														<i class="ti-pencil-alt"></i>
													</button>
													
													<button title='Disable' id="{{ $incentive->id }}" onclick="disable(this.id)"
														class="btn btn-rounded btn-danger btn-icon">
														<i class="fa fa-ban"></i>
													</button>
													@endif
												@else
													<button title='Activate' id="{{ $incentive->id }}" onclick="activate(this.id)"
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
	@foreach ($incentives as $incentive)
		@include('incentives.edit_incentive')
	@endforeach
	@include('incentives.new_incentive')
@endsection
@section('incentivescript')
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
							url: "disable-incentive/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("incentive has been disable!", {
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
						swal("incentive is safe!");
					}
				});
		}

		function activate(id) {
			var element = document.getElementById('tdActionId' + id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "Once activated, you can edit and disable the incentive!",
					icon: "info",
					buttons: true,
					dangerMode: true,
				})
				.then((willActivate) => {
					if (willActivate) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "activate-incentive/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("incentive has been activated!", {
									icon: "success",
								}).then(function() {
									document.getElementById("tdId" + id).innerHTML =
										"<label class='badge badge-success'>Active</label>";
									document.getElementById("tdActionId" + dataID).innerHTML =
										"<button type='button' id='edit" + id +
										"' class='btn btn-info btn-rounded btn-icon' data-target='#edit_incentive" +
										id +
										"' data-toggle='modal' title='Disable'>	<i class='ti-pencil-alt'></i></button> <button title='Disable' id='action" +
										id + "' onclick = 'disable(" + id +
										")' class = 'btn btn-rounded btn-danger btn-icon'><i class='fa fa-ban'></i></button > ";
									// document.getElementById(id).remove();
								});
							}
						})

					} else {
						swal("incentive is safe!");
					}
				});
		}
	</script>
@endsection
