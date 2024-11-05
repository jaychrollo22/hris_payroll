@extends('layouts.header')

@section('content')
<div class="main-panel">
	<div class="content-wrapper">

		<div class="col-lg-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<p class="card-description">
						<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal"
							data-target="#newEmpDeduction">
							<i class="ti-plus btn-icon-prepend"></i>
							New Employee Deduction
						</button>
						<button type="button" class="btn btn-outline-primary btn-icon-text" data-toggle="modal" data-target="#importEmployeeDeduction">
							<i class="ti-plus btn-icon-prepend"></i>                                                    
							Import Employee Deduction
						  </button>
					</p>

					<h4 class="card-title">Employee Deductions </h4>
						@if ($errors->any())
							@foreach ($errors->all() as $error)
								<div class="alert alert-danger alert-dismissible fade show" role="alert">
									{{ $error }}

								</div>
							@endforeach
						@endif
					<h4 class="card-title">Filter</h4>
					<p class="card-description">
					<form method='get' onsubmit='show();' enctype="multipart/form-data">
						<div class=row>
							<div class='col-md-4'>
								<div class="form-group">
									<label class="text-right">Company</label>
									<select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company' required>
										<option value="">-- Select Company --</option>
										@foreach($companies as $comp)
										<option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_name}} - {{$comp->company_code}}</option>
										@endforeach
									</select>
								</div>
							</div>
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
					<a href="/employee-deduction-export?company={{$company}}&status={{$status}}" title="Export" class="btn btn-outline-primary btn-icon-text btn-sm text-center mb-2"><i class="ti-arrow-down btn-icon-prepend"></i></a>
					

					<div class="table-responsive">
						<table class="table table-hover table-bordered tablewithSearch">
							<thead>
								<tr>
									<th>User ID</th>
									<th>Employee</th>
									<th>Deduction</th>
                                    <th>Amount</th>
                                    <th>No. of Years Deduction</th>
                                    <th>Amortization</th>
                                    <th>Type of Deduction</th>
									<th>Status</th>
									<th>Date Modified</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($employee_deductions as $employee_deduction)
									<tr>
										<td>
											<a href="/edit-employee-deduction/{{$employee_deduction->id}}" target="_blank" class="ml-3 mr-3">
												<i class="ti-pencil"></i>
											</a>
											{{ $employee_deduction->employee ? $employee_deduction->employee->user_id : "" }}
										</td>
										<td>
											{{ $employee_deduction->employee ? $employee_deduction->employee->last_name . ', ' . $employee_deduction->employee->first_name . ' ' . $employee_deduction->employee->middle_name : "" }}
											<br>
											<small>{{$employee_deduction->employee ? $employee_deduction->employee->company->company_name : ""}}</small>
										</td>
										<td>
											
											{{ $employee_deduction->deduction ? $employee_deduction->deduction->name : "" }}
										</td>

										<td>{{ number_format($employee_deduction->amount) }}</td>
										<td>{{ $employee_deduction->no_of_years_deduction }}</td>
										<td>{{ number_format($employee_deduction->amortization) }}</td>
										<td>{{ $employee_deduction->type_of_deduction }}</td>

										<td id="tdId{{ $employee_deduction->id }}">
											@if ($employee_deduction->status == 'Active')
												<label id="status{{ $employee_deduction->id }}"
													class="badge badge-success">{{ $employee_deduction->status }}</label>
											@else
												<label id="status{{ $employee_deduction->id }}"
													class="badge badge-danger">{{ $employee_deduction->status }}</label>
											@endif
										</td>
										<td>{{ $employee_deduction->updated_at ? date('Y-m-d', strtotime($employee_deduction->updated_at)) : "" }}</td>
										<td id="tdActionId{{ $employee_deduction->id }}" data-id="{{ $employee_deduction->id }}" align="center">
											@if ($employee_deduction->status == 'Active')
												<i id="{{ $employee_deduction->id }}" class="fa fa-ban text-warning" title="Inactive" onclick="disable(this.id)" style="cursor:pointer;font-size:1.5em;"></i>
											@endif
											<i id="{{ $employee_deduction->id }}" class="fa fa-trash text-danger" title="Delete" onclick="deleteDeduction(this.id)" style="cursor:pointer;font-size:1.5em;"></i>
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
@include('employee_deductions.import')
@include('employee_deductions.create')
@endsection
@section('empAllowScript')
	<script>
		function disable(id) {
			var element = document.getElementById('tdActionId' + id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "You want to disable this Employee Deduction?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDisable) => {
					if (willDisable) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "disable-employee-deduction/" + id,
							method: "GET",
							data: {
								id: id
							},
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Employee Deduction has been disabled!", {
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
		function deleteDeduction(id) {
			var element = document.getElementById('tdActionId' + id);
			var dataID = element.getAttribute('data-id');
			swal({
					title: "Are you sure?",
					text: "You want to delete this Employee Deduction",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDisable) => {
					if (willDisable) {
						document.getElementById("loader").style.display = "block";
						$.ajax({
							url: "delete-employee-deduction/" + id,
							method: "GET",
							headers: {
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							success: function(data) {
								document.getElementById("loader").style.display = "none";
								swal("Employee Deduction has been deleted!", {
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
