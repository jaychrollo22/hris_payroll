@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Employee Group</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal" data-target="#newEmpGroup">
								<i class="ti-plus btn-icon-prepend"></i>
								New Employee Group
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
										<th>Employee Count</th>
										<th>Company</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($companies as $company)
										<tr>
											<td>{{ count($company->employee_company) }}</td>
											<td>{{ $company->company_name }}</td>
											<td>
												{{-- <button type="button" class="btn btn-primary">Edit</button> --}}
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
	@include('employee_companies.new_emp_group')
@endsection
@section('empCompanyScript')
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
