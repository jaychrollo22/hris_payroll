@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class='row'>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Employee Allowance for {{ $employee_allowance->employee->first_name . ' ' . $employee_allowance->employee->last_name }}</h4>
                        <div class="col-md-12">
							<form method='POST' action='{{url('update-employee-allowance/'.$employee_allowance->id)}}' onsubmit='show()'>
								@csrf
								<div class="modal-body">
									<div class="row">
										<div class='col-lg-6 form-group'>
											<label for="allowanceType">Allowance Type</label>
											<select data-placeholder="Select Allowance Type"
												class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='allowance_type'
												disabled>
												<option value="">--Select Allowance Type--</option>
												@foreach ($allowanceTypes as $allowanceType)
													<option value="{{ $allowanceType->id }}" {{ $employee_allowance->allowance_id == $allowanceType->id ? 'selected' : '' }}>
														{{ $allowanceType->name }}</option>
												@endforeach
											</select>
				
										</div>
										<div class="col-lg-6 form-group">
											<label for="employee">Employee</label>
											<select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-single "
												style='width:100%;' name='user_id' disabled>
												<option value="">--Select Employee--</option>
												@foreach ($employees as $employee)
													<option value="{{ $employee->user_id }}" {{ $employee_allowance->user_id == $employee->user_id ? 'selected' : '' }}>
														{{ $employee->last_name . ', ' . $employee->first_name . ' ' . $employee->middle_name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 form-group">
											<label for="amount">Amount</label>
											<input type="number" class="form-control form-control-sm" name="amount" id="amount"  required min="1"
												value="{{ $employee_allowance->allowance_amount }}" placeholder="0.00">
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 form-group">
											<label for="schedule">Schedule</label>
											<input type="text" class="form-control form-control-sm" name="schedule" id="schedule"
												value="{{ $employee_allowance->schedule }}" placeholder="Schedule">
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<a href="/employee-allowance" type="button" class="btn btn-secondary" data-dismiss="modal">Close</a>
									<button type="submit" class="btn btn-primary">Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
{{-- 

<div class="modal fade" id="editEmpAllowance{{$employee_allowance->id}}" tabindex="-1" role="dialog" aria-labelledby="editEmpAllowancelabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newEmpAllowancelabel">Edit Employee Allowance</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='edit-employee-allowance/{{ $employee_allowance->id }}' onsubmit='show()'>
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class='col-lg-6 form-group'>
							<label for="allowanceType">Allowance Type</label>
							<select data-placeholder="Select Allowance Type"
								class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='allowance_type'
								disabled>
								<option value="">--Select Allowance Type--</option>
								@foreach ($allowanceTypes as $allowanceType)
									<option value="{{ $allowanceType->id }}" {{ $employee_allowance->allowance_id == $allowanceType->id ? 'selected' : '' }}>
										{{ $allowanceType->name }}</option>
								@endforeach
							</select>

						</div>
						<div class="col-lg-6 form-group">
							<label for="employee">Employee</label>
							<select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-single "
								style='width:100%;' name='user_id' disabled>
								<option value="">--Select Employee--</option>
								@foreach ($employees as $employee)
									<option value="{{ $employee->user_id }}" {{ $employee_allowance->user_id == $employee->user_id ? 'selected' : '' }}>
										{{ $employee->last_name . ', ' . $employee->first_name . ' ' . $employee->middle_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 form-group">
							<label for="amount">Amount</label>
							<input type="number" class="form-control form-control-sm" name="amount" id="amount"  required min="1"
								value="{{ $employee_allowance->allowance_amount }}" placeholder="0.00">
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 form-group">
							<label for="schedule">Schedule</label>
							<input type="text" class="form-control form-control-sm" name="schedule" id="schedule"
								value="{{ $employee_allowance->schedule }}" placeholder="Schedule">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
	</div>
</div> --}}
