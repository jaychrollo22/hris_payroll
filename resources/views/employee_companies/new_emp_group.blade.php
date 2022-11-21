<div class="modal fade" id="newEmpGroup" tabindex="-1" role="dialog" aria-labelledby="newEmpGrouplabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newEmpGrouplabel">New Employee Group</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='new-employee-group' onsubmit='show()'>
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class='col-lg-6 form-group'>
							<label for="allowanceType">Company</label>
							<select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single "
								style='width:100%;' name='company' required>
								<option value="">--Select Company</option>
								@foreach ($companies as $company)
									<option value="{{ $company->id }}" {{ old('company') == $company->id ? 'selected' : '' }}>
										{{ $company->company_name }}</option>
								@endforeach
							</select>

						</div>
						<div class="col-lg-6 form-group">
							<label for="employee">Employee</label>
							<select data-placeholder="Select Employee"
								class="form-control form-control-sm required js-example-basic-multiple w-100" multiple="multiple"
								style='width:100%;' name='emp_code[]' required>
								<option value="">--Select Employee--</option>
								@foreach ($employees as $employee)
									<option value="{{ $employee->emp_code }}"> {{ $employee->last_name . ', ' . $employee->first_name . ' ' . $employee->middle_name }} - {{ $employee->emp_code }}</option>
								@endforeach
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
