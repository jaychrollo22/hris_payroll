<div class="modal fade" id="newLeaveCredits" tabindex="-1" role="dialog" aria-labelledby="newLeaveCreditslabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newLeaveCreditslabel">New Employee Leave Credit</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='new-employee-leave-credit' onsubmit='show()'>
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class='col-lg-6 form-group'>
							<label for="leaveType">Leave Type</label>
							<select data-placeholder="Select Leave Type"
								class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='leave_type'
								required>
								<option value="">--Select Leave Type--</option>
								@foreach ($leaveTypes as $leaveType)
									<option value="{{ $leaveType->id }}" {{ old('leaveType') == $leaveType->id ? 'selected' : '' }}>
										{{ $leaveType->leave_type }}</option>
								@endforeach
							</select>

						</div>
						<div class="col-lg-6 form-group">
							<label for="employee">Employee</label>
							<select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-multiple "
								style='width:100%;' name='user_id' required>
								<option value="">--Select Employee--</option>
								@foreach ($employees_selection as $employee)
									<option value="{{ $employee->user_id }}" {{ old('employee') == $employee->user_id ? 'selected' : '' }}>
										{{ $employee->last_name . ', ' . $employee->first_name . ' ' . $employee->middle_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 form-group">
							<label for="count">Count (days)</label>
							<input type="number" class="form-control form-control-sm" name="count" id="count" required min=".00" step='0.01'
								value="{{ old('count') }}" placeholder="0.00">
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
