<div class="modal fade" id="newLoan" tabindex="-1" role="dialog" aria-labelledby="newLoan" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newLoanlabel">Loan Registration</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method='POST' action='new-loan' onsubmit='show()'>
					@csrf
					<div class="row">
						<div class="col-lg-6 form-group">
							<label for="loanType">Loan Type</label>
							<select data-placeholder="Select Loan Type"
								class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='loan_type'
								required>
								<option value="">--Select Loan Type--</option>
								@foreach ($loanTypes as $loanType)
									<option value="{{ $loanType->id }}" {{ old('loan_type') == $loanType->id ? 'selected' : '' }}>
										{{ $loanType->loan_name }}</option>
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
						<div class="col-lg-6 form-group">
							<label for="amount">Amount</label>
							<input type="number" class="form-control form-control-sm" name="amount" id="amount" required min="1"
								value="{{ old('amount') }}" placeholder="0.00">
						</div>
						<div class="col-lg-6 form-group">
							<label for="ammortAmt">Ammortization Amount</label>
							<input type="number" class="form-control form-control-sm" name="monthly_ammort_amt" id="monthly_ammort_amt"
								required min="1" placeholder="0.00" value="{{ old('monthly_ammort_amt') }}">
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 form-group">
							<label for="startDate">Start Date</label>
							<input type="date" class="form-control form-control-sm" name="start_date" id="start_date"
								value="{{ old('start_date') }}">
						</div>
						<div class="col-lg-6 form-group">
							<label for="expiryDate">End Date</label>
							<input type="date" class="form-control form-control-sm" name="expiry_date" id="start_date"
								value="{{ old('expiry_date') }}">
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 form-group">
							<label for="amount">Initial Amount</label>
							<input type="number" class="form-control form-control-sm" name="initial_amount" id="amount" required
								min="1" value="{{ old('initial_amount') }}" placeholder="0.00">
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
