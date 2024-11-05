<div class="modal fade" id="newEmpDeduction" tabindex="-1" role="dialog" aria-labelledby="newEmpDeductionlabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newEmpDeductionlabel">New Employee Deduction</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='new-employee-deduction' onsubmit='show()'>
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class='col-lg-6 form-group'>
							<label for="deductionType">Deduction</label>
							<select data-placeholder="Select Deduction"
								class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='deduction_id'
								required>
								<option value="">--Select Deduction --</option>
								@foreach ($deductionTypes as $deductionType)
									<option value="{{ $deductionType->id }}" {{ old('deduction_id') == $deductionType->id ? 'selected' : '' }}>
										{{ $deductionType->name }}</option>
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
							<label for="amount">Amount</label>
							<input type="number" class="form-control form-control-sm" name="amount" id="amount" required min="1"
								value="{{ old('amount') }}" placeholder="0.00">
						</div>
						<div class="col-lg-6 form-group">
							<label for="amount">No. of years Deduction</label>
							<input type="number" class="form-control form-control-sm" name="no_of_years_deduction" id="no_of_years_deduction" required min="1"
								value="{{ old('no_of_years_deduction') }}" placeholder="0.00">
						</div>
					</div>
					<div class="row">
                        <div class="col-lg-6 form-group">
							<label for="amortization">Amortization</label>
							<input type="number" class="form-control form-control-sm" name="amortization" id="amortization" required min="1"
								value="{{ old('amortization') }}" placeholder="0.00">
						</div>
						<div class="col-lg-6 form-group">
							<label for="type_of_deduction">Type of Deduction</label>
							<select name="type_of_deduction" id="type_of_deduction" class="form-control form-control-sm">
								<option value="">Choose Type of Deduction</option>
								<option value="First Cut-Off" {{ old('type_of_deduction') == "First Cut-Off" ? 'selected' : '' }}>First Cut-Off</option>
								<option value="Second Cut-Off" {{ old('type_of_deduction') == "Second Cut-Off" ? 'selected' : '' }}>Second Cut-Off</option>
								<option value="Every Cut-Off" {{ old('type_of_deduction') == "Every Cut-Off" ? 'selected' : '' }}>Every Cut-Off</option>
							</select>
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
