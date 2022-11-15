@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Loan Types</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newloanType">
								<i class="ti-plus btn-icon-prepend"></i>
								New Loan Type
							</button>
						</p>

						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>Loan Type</th>
										<th>Date Created</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($loanTypes as $loanType)
										<tr>
											<td>{{ $loanType->loan_name }}</td>
											<td>{{ date('M d Y ', strtotime($loanType->created_at)) }}</td>
											<td id="tdId{{ $loanType->id }}">
												@if ($loanType->status == 'Active')
													<label id="status{{ $loanType->id }}" class="badge badge-success">{{ $loanType->status }}</label>
												@else
													<label id="status{{ $loanType->id }}" class="badge badge-danger">{{ $loanType->status }}</label>
												@endif
											</td>
											<td id="tdActionId{{ $loanType->id }}" data-id="{{ $loanType->id }}">
												@if ($loanType->status == 'Active')
													<button title='Disable' id="{{ $loanType->id }}" onclick="disable(this.id)"
														class="btn btn-rounded btn-danger btn-icon">
														<i class="fa fa-ban"></i>
													</button>
												@else
													<button title='Activate' id="{{ $loanType->id }}" onclick="activate(this.id)"
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
	{{-- @foreach ($companies as $company)
		@include('loanTypes.edit_loanType')
	@endforeach --}}
	@include('masterfiles.new_loanType')
@endsection
@section('masterfilesScript')
	<script>
		function disable(id) {
			var element = document.getElementById('tdActionId' + id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "Once disabled, you will not be able to use this in other modules!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDisable) => {
					if (willDisable) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "disable-loanType/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Loan type has been disable!", {
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
						swal("Loan type is safe!");
					}
				});
		}

		function activate(id) {
			var element = document.getElementById('tdActionId' + id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "Once activated, you can disable the loan type!",
					icon: "info",
					buttons: true,
					dangerMode: true,
				})
				.then((willActivate) => {
					if (willActivate) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "enable-loanType/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Loan type has been activated!", {
									icon: "success",
								}).then(function() {
									document.getElementById("tdId" + id).innerHTML =
										"<label class='badge badge-success'>Active</label>";
									document.getElementById("tdActionId" + dataID).innerHTML =
										"<button title='Disable' id='action" +
										id + "' onclick = 'disable(" + id +
										")' class = 'btn btn-rounded btn-danger btn-icon'><i class='fa fa-ban'></i></button > ";
									// document.getElementById(id).remove();
								});
							}
						})

					} else {
						swal("Loan type is safe!");
					}
				});
		}
	</script>
@endsection
