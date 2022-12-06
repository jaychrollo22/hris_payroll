@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Employee Incentives</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal"
								data-target="#newEmpIncentive">
								<i class="ti-plus btn-icon-prepend"></i>
								New Employee Leave Credit
							</button>
						</p>
						@if ($errors->any())
							@foreach ($errors->all() as $error)
								<div class="alert alert-danger alert-dismissible fade show" role="alert">
									{{ $error }}
								</div>
							@endforeach
						@endif
						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>Incentive Type</th>
										<th>Employee</th>
										<th>Amount</th>
										<th>Date Created</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($employeeIncentives as $employeeIncentive)
										<tr>
											<td>{{ $employeeIncentive->incentive->name }}</td>
											<td>
												{{ $employeeIncentive->employee->last_name . ', ' . $employeeIncentive->employee->first_name . ' ' . $employeeIncentive->employee->middle_name }}
											</td>
											<td>{{ number_format($employeeIncentive->incentive_amount) }}</td>
											<td>{{ date('M d, Y', strtotime($employeeIncentive->created_at)) }}</td>
											<td id="tdId{{ $employeeIncentive->id }}">
												@if ($employeeIncentive->status == 'Active')
													<label id="status{{ $employeeIncentive->id }}"
														class="badge badge-success">{{ $employeeIncentive->status }}</label>
												@else
													<label id="status{{ $employeeIncentive->id }}"
														class="badge badge-danger">{{ $employeeIncentive->status }}</label>
												@endif
											</td>
											<td id="tdActionId{{ $employeeIncentive->id }}" data-id="{{ $employeeIncentive->id }}">
												@if ($employeeIncentive->status == 'Active')
													<button title='Disable' id="{{ $employeeIncentive->id }}" onclick="disable(this.id)"
														class="btn btn-rounded btn-danger btn-icon">
														<i class="fa fa-ban"></i>
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
	@include('employee_incentives.new_emp_incentive')
@endsection
@section('empIncentiveScript')
	<script>
		function disable(id) {
			var element = document.getElementById('tdActionId' + id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "Once disabled, you will not be able to recover this data!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDisable) => {
					if (willDisable) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "disableEmp-incentive/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Employee Incentive has been disable!", {
									icon: "success",
								}).then(function() {
									document.getElementById("tdId" + id).innerHTML =
										"<label class='badge badge-danger'>Inactive</label>";
									document.querySelector('#tdActionId' + id).innerHTML = "";
								});
							}
						})

					} else {
						swal("Employee Incentive is safe!");
					}
				});
		}
	</script>
@endsection
