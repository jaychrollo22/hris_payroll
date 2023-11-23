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
							<label for="allowanceType">Particular</label>
							<select data-placeholder="Select Particular"
								class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='allowance_type'
								required>
								<option value="">--Select Particular--</option>
								@foreach ($allowanceTypes as $allowanceType)
									<option value="{{ $allowanceType->id }}" {{ old('allowance') == $allowanceType->id ? 'selected' : '' }}>
										{{ $allowanceType->name }}</option>
								@endforeach
							</select>

						</div>
						<div class="col-lg-6 form-group">
							<label for="employee">Employee</label>
							<select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-single "
								style='width:100%;' name='user_id' required>
								<option value="">--Select Employee--</option>
								@foreach ($employees as $employee)
									<option value="{{ $employee->user_id }}">
										{{ $employee->last_name . ', ' . $employee->first_name . ' ' . $employee->middle_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="row">
						
						<div class="col-lg-6 form-group">
							<label for="description">Description</label>
							<input type="text" class="form-control form-control-sm" name="description" id="description"
								value="{{ old('description') }}" placeholder="Any Remarks">
						</div>
						<div class="col-lg-6 form-group">
							<label for="application">Application</label>
							<select id="application" class="form-control form-control-sm" name="application">
								<option value="">Choose Application</option>
								<option value="Daily">Daily</option>
								<option value="Fixed">Fixed</option>
							</select>
						</div>
						<div class="col-lg-6 form-group">
							<label for="type">Type</label>
							<select name="type" id="type" class="form-control form-control-sm">
								<option value="">Choose Type</option>
								<option value="Basic Allowance">Basic Allowance</option>
								<option value="Grade Allowance">Grade Allowance</option>
							</select>
						</div>

						<div class="col-lg-6 form-group">
							<label for="schedule">Credit Schedule</label>
							<select name="schedule" id="schedule" class="form-control form-control-sm">
								<option value="">Choose Credit Schedule</option>
								<option value="First Cut-Off">First Cut-Off</option>
								<option value="Last Cut-Off">Last Cut-Off</option>
								<option value="Every Cut-Off">Every Cut-Off</option>
							</select>
						</div>
						<div class="col-lg-6 form-group">
							<label for="amount">Amount</label>
							<input type="number" class="form-control form-control-sm" name="amount" id="amount" required min="1"
								value="{{ old('amount') }}" placeholder="0.00">
						</div>
						<div class="col-lg-6 form-group">
							<label for="end_date">End Date</label>
							<input type="date" class="form-control form-control-sm" name="end_date" id="end_date"
								value="{{ old('schend_dateedule') }}">
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
