<div class="modal fade" id="newEmployeeLoan" tabindex="-1" role="dialog" aria-labelledby="newEmployeeLoanlabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newEmployeeLoanlabel">New Employee Loan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='new-employee-loan' onsubmit='show()' onsubmit="btnNewLoan.disabled = true; return true;">
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
										{{ $employee->last_name . ', ' . $employee->first_name . ' ' . $employee->middle_name }} ({{$employee->original_date_hired}})</option>
								@endforeach
							</select>
						</div>
					
						<div class="col-lg-4 form-group">
							<label for="reference_id_number">Reference ID Number</label>
							<input type="text" class="form-control form-control-sm" name="reference_id_number" id="reference_id_number"
								value="{{ old('reference_id_number') }}" placeholder="Reference ID Number">
						</div>
						<div class="col-lg-4 form-group">
							<label for="collection_date">Collection Date</label>
							<input type="date" class="form-control form-control-sm" name="collection_date" id="collection_date"
								value="{{ old('collection_date') }}" placeholder="">
						</div>
						<div class="col-lg-4 form-group">
							<label for="due_date">Due Date</label>
							<input type="date" class="form-control form-control-sm" name="due_date" id="due_date"
								value="{{ old('due_date') }}" placeholder="">
						</div>
						<div class="col-lg-4 form-group">
							<label for="particular">Particular</label>
                            <select data-placeholder="Select Particular" class="form-control form-control-sm required js-example-basic-multiple "
                            style='width:100%;' name='particular' required>
                                <option value="">--Select Particular--</option>
                                <option value="SSS">SSS</option>
                                <option value="HDMF">HDMF</option>
                                <option value="Personal">Personal</option>
                                <option value="Insurance">Insurance</option>
                                <option value="Miscellaneous">Miscellaneous</option>
                                <option value="Others">Others</option>
                            </select>
						</div>
                        <div class="col-lg-4 form-group">
							<label for="description">Description</label>
							<input type="text" class="form-control form-control-sm" name="description" id="description"
								value="{{ old('description') }}" placeholder="">
						</div>
                        <div class="col-lg-4 form-group">
							<label for="particular">Credit Schedule</label>
                            <select data-placeholder="Select Credit Schedule" class="form-control form-control-sm required js-example-basic-multiple "
                            style='width:100%;' name='credit_schedule' required>
                                <option value="">--Select Credit Schedule--</option>
                                <option value="First Cut-Off">First Cut-Off</option>
                                <option value="Last Cut-Off">Last Cut-Off</option>
                                <option value="Every Cut-Off">Every Cut-Off</option>
                            </select>
						</div>

                        <div class='col-lg-4 form-group'>
							<label for="credit_company">Credit Company</label>
							<select data-placeholder="Select Credit Company"
								class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='credit_company'
								required>
								<option value="">--Select Credit Company--</option>
								@foreach ($companies as $company)
									<option value="{{ $company->id }}">
										{{ $company->company_name }} - {{ $company->company_code }}</option>
								@endforeach
							</select>
						</div>
                        <div class='col-lg-4 form-group'>
							<label for="credit_branch">Credit Branch</label>
							<select data-placeholder="Select Credit Branch"
								class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='credit_branch'
								required>
								<option value="">--Select Credit Branch--</option>
								<option value="MAIN BRANCH">MAIN BRANCH</option>
							</select>
						</div>
                        <div class='col-lg-4 form-group'>
							<label for="payable_amount">Payable Amount</label>
							<input type="number" class="form-control form-control-sm" name="payable_amount" id="description"
								value="{{ old('description') }}" min=".00" step='0.01' placeholder="0.00">
						</div>
                        <div class='col-lg-4 form-group'>
							<label for="payable_adjustment">Payable Adjustment</label>
							<input type="number" class="form-control form-control-sm" name="payable_adjustment" id="payable_adjustment"
								value="{{ old('payable_adjustment') }}" min=".00" step='0.01' placeholder="0.00">
						</div>
                        <div class='col-lg-4 form-group'>
							<label for="credit_branch">Outright Deduction Bolean</label>
							<select data-placeholder="Select Outright Deduction Bolean"
								class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='outright_deduction_bolean'
								required>
								<option value="">--Select Outright Deduction Bolean--</option>
								<option value="1">True</option>
								<option value="0">False</option>
							</select>
						</div>
                        <div class='col-lg-4 form-group'>
							<label for="monthly_deduction">Monthly Deduction</label>
							<input type="number" class="form-control form-control-sm" name="monthly_deduction" id="monthly_deduction"
								value="{{ old('monthly_deduction') }}" min=".00" step='0.01' placeholder="0.00">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button id="btnNewLoan" name="btnNewLoan" type="submit" class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
