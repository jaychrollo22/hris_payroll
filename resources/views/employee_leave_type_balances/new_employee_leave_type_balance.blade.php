<div class="modal fade" id="newEmployeeLeaveTypeBalance" tabindex="-1" role="dialog" aria-labelledby="newEmployeeLeaveTypeBalancelabel"
	aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newEmployeeLeaveTypeBalancelabel">New Employee Leave Type Balance</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='new-employee-leave-type-balance' onsubmit='show()' onsubmit="btnNewLeave.disabled = true; return true;">
				@csrf
				<div class="modal-body">
					<div class="row">
                        <div class="col-lg-12 form-group">
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

						<div class='col-lg-12 form-group'>
							<label for="leaveType">Leave Type</label>
							<select data-placeholder="Select Leave Type"
								class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='leave_type'
								required>
								<option value="">--Select Leave Type--</option>
								@foreach ($leave_types as $leaveType)
									<option value="{{ $leaveType->code }}">
										{{ $leaveType->leave_type }}</option>
								@endforeach
							</select>
						</div>
					
						<div class="col-lg-12 form-group">
							<label for="balance">Leave Balance</label>
							<input type="number" class="form-control form-control-sm" name="balance" id="balance" required min=".00" step='0.01'
								value="{{ old('balance') }}" placeholder="0.00">
						</div>

						<div class="col-lg-12 form-group">
							<label for="year">Year</label>
							<input type="number" class="form-control form-control-sm" name="year" id="year" required
								value="{{ old('year') }}" placeholder="0000" >
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button id="btnNewLeave" type="submit" class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
