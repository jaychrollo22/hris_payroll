<div class="modal fade" id="newEmpAllowance" tabindex="-1" role="dialog" aria-labelledby="newEmpAllowancelabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newEmpAllowancelabel">New Employee Allowance</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='new-employee-allowance' onsubmit='show()'>
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class='col-lg-6 form-group'>
							<label for="allowanceType">Allowance Type</label>
							<select data-placeholder="Select Allowance Type"
								class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='allowance_type'
								required>
								<option value="">--Select Allowance Type--</option>
								@foreach ($allowanceTypes as $allowanceType)
									<option value="{{ $allowanceType->id }}" {{ old('allowance') == $allowanceType->id ? 'selected' : '' }}>
										{{ $allowanceType->name }}</option>
								@endforeach
							</select>

						</div>
						<div class="col-lg-6 form-group">
							<label for="employee">Employee</label>
							<select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-single "
								style='width:100%;' name='employee' required>
								<option value="">--Select Employee--</option>
								@foreach ($employees as $employee)
									<option value="{{ $employee->id }}" {{ old('employee') == $employee->id ? 'selected' : '' }}>
										{{ $employee->last_name . ', ' . $employee->first_name . ' ' . $employee->middle_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 form-group">
							<label for="amount">Amount</label>
							<input type="number" class="form-control form-control-sm" name="amount" id="amount" required min="1"
								value="{{ old('amount') }}" placeholder="0.00">
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 form-group">
							<label for="schedule">Schedule</label>
							<input type="text" class="form-control form-control-sm" name="schedule" id="schedule"
								value="{{ old('schedule') }}" placeholder="Schedule">
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
</div>
