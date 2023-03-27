@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Employee Allowances</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal"
								data-target="#newEmpAllowance">
								<i class="ti-plus btn-icon-prepend"></i>
								New Employee Allowance
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
										<th>Allowance Type</th>
										<th>Employee</th>
										<th>Amount</th>
										<th>Schedule</th>
										<th>Date Created</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($employeeAllowances as $employeeAllowance)
										<tr>
											<td>{{ $employeeAllowance->allowance->name }}</td>
											<td>
												{{ $employeeAllowance->employee->last_name . ', ' . $employeeAllowance->employee->first_name . ' ' . $employeeAllowance->employee->middle_name }}
											</td>
											<td>{{ number_format($employeeAllowance->allowance_amount) }}</td>
											<td>{{ $employeeAllowance->schedule }}</td>
											<td>{{ date('M d, Y', strtotime($employeeAllowance->created_at)) }}</td>
											<td id="tdId{{ $employeeAllowance->id }}">
												@if ($employeeAllowance->status == 'Active')
													<label id="status{{ $employeeAllowance->id }}"
														class="badge badge-success">{{ $employeeAllowance->status }}</label>
												@else
													<label id="status{{ $employeeAllowance->id }}"
														class="badge badge-danger">{{ $employeeAllowance->status }}</label>
												@endif
											</td>
											<td id="tdActionId{{ $employeeAllowance->id }}" data-id="{{ $employeeAllowance->id }}">
												@if ($employeeAllowance->status == 'Active')
													<button title='Disable' id="{{ $employeeAllowance->id }}" onclick="disable(this.id)"
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
	@include('employee_allowances.new_emp_allowance')
@endsection
@section('empAllowScript')
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
							url: "disableEmp-allowance/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Employee Allowance has been disable!", {
									icon: "success",
								}).then(function() {
									document.getElementById("tdId" + id).innerHTML =
										"<label class='badge badge-danger'>Inactive</label>";
									document.querySelector('#tdActionId' + id).innerHTML = "";
								});
							}
						})

					} else {
						swal("Employee allowance is safe!");
					}
				});
		}
	</script>
@endsection
