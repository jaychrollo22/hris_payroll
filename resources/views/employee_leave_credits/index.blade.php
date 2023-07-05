@extends('layouts.header')

@section('content')
	<div class="main-panel">
		<div class="content-wrapper">

			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Employee Leave Credit</h4>
						<p class="card-description">
							<button type="button" class="btn btn-outline-success btn-icon-text" data-toggle="modal"
								data-target="#newLeaveCredits">
								<i class="ti-plus btn-icon-prepend"></i>
								New Leave Credit
							</button>
						</p>
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
										<select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company' required>
											<option value="">-- Select Company --</option>
											@foreach($companies as $comp)
											<option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_name}} - {{$comp->company_code}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class='col-md-3'>
									<div class="form-group">
										
                                        <select data-placeholder="Select Department" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='department'>
                                            <option value="">-- Select Department --</option>
                                            @foreach($departments as $dep)
                                            <option value="{{$dep->id}}" @if ($dep->id == $department) selected @endif>{{$dep->name}} - {{$dep->code}}</option>
                                            @endforeach
                                        </select>
										
									</div>
								</div>
								<div class='col-md-2'>
									<button type="submit" class="form-control form-control-sm btn btn-primary mb-2 btn-sm">Filter</button>
								</div>
							</div>
							
						</form>
						</p>

						<div class="table-responsive">
							<table class="table table-hover table-bordered tablewithSearch">
								<thead>
									<tr>
										<th>User ID</th>
										<th>Employee</th>
										<th>Company</th>
										<th>Department</th>
										<th>Date Hired</th>
										<th>Leave Credits</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($employees as $employee)
										@if(count($employee->employee_leave_credits) > 0)
											<tr>
												<td>{{ $employee->user_id}}</td>
												<td>{{ $employee->first_name . ' ' . $employee->last_name}}</td>
												<td>{{ $employee->company->company_name}}</td>
												<td>{{ $employee->department->name}}</td>
												<td>{{ $employee->original_date_hired}}</td>
												<td>
													<ul>
														@foreach ($employee->employee_leave_credits as $leave_credit)
															<li>{{$leave_credit->leave->leave_type . ' : ' . $leave_credit->count}}</li>
														@endforeach
													</ul>
												</td>
											</tr>
										@endif
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
	@include('employee_leave_credits.new_leave_credit')


@endsection
@section('leaveCreditScript')
	<script>
		
	</script>
@endsection
