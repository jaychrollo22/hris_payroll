<div class="modal fade" id="newEmpIncentive" tabindex="-1" role="dialog" aria-labelledby="newEmpIncentivelabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newEmpIncentivelabel">New Employee incentive</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='new-employee-incentive' onsubmit='show()'>
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class='col-lg-6 form-group'>
							<label for="allowanceType">Incentive Type</label>
							<select data-placeholder="Select Allowance Type"
								class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='incentive_type'
								required>
								<option value="">--Select Allowance Type--</option>
								@foreach ($incentiveTypes as $incentiveType)
									<option value="{{ $incentiveType->id }}" {{ old('allowance') == $incentiveType->id ? 'selected' : '' }}>
										{{ $incentiveType->name }}</option>
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
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
