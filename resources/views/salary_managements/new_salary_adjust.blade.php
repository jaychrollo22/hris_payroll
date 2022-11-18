<div class="modal fade" id="newSalaryAdjustment" tabindex="-1" role="dialog" aria-labelledby="newSalaryAdjustment"
	aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newSalaryAdjustmentlabel">New Salary Adjustment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='new-employee-allowance' onsubmit='show()'>
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-4 form-group">
							<label for="employee">Employee:*</label>
							<select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-single "
								style='width:100%;' name='employee' required>
								<option value="">--Select Employee--</option>
								@foreach ($employees as $employee)
									<option value="{{ $employee->id }}" {{ old('employee') == $employee->id ? 'selected' : '' }}>
										{{ $employee->last_name . ', ' . $employee->first_name . ' ' . $employee->middle_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-4 form-group">
							<label for="employee">Type:*</label>
							<select data-placeholder="Select Type" class="form-control form-control-sm required js-example-basic-single "
								style='width:100%;' name='type' required>
								<option value="">--Select Type--</option>
								<option value="gross">Gross</option>
								<option value="net">Net</option>
							</select>
						</div>
						<div class="col-lg-4 form-group">
							<label for="category">Category:*</label>
							<input type="text" name='category' class="form-control form-control-sm" required>
						</div>

					</div>

					<div class="row">
						<div class="col-lg-4 form-group">
							<label for="amount">Amount:*</label>
							<input type="number" class="form-control form-control-sm" name="amount" id="amount" required min="1"
								value="{{ old('amount') }}" placeholder="0.00" required>
						</div>
						<div class="col-lg-8 form-group">
							<label for="description">Description:*</label>
							<input type="text" name='description' class="form-control form-control-sm" required>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 form-group">
							<label for="remarks">Remarks:</label>
							<textarea class="form-control form-control-sm" id="exampleTextarea1" rows="3" name="remarks"></textarea>
						</div>
					</div>
					<hr>
					<div class="repeater">
						<h5>Employee Allowance
							<button type="button" data-repeater-create class="btn btn-icon btn-rounded btn-success"> <i
									class="fa fa-plus"></i></button>
						</h5>
						<form action="" class="repeater">
							<div data-repeater-list="group-a">
								<div data-repeater-item class="mb-2">
									<div class="input-group mb-2 mr-sm-2 mb-sm-0">
										<span class="input-group-text">Allowance Type and Amount</span>
										<select data-placeholder="Select Allowance Type"
											class="form-control form-control required js-example-basic-single " style='width:50%;' name='allowance_type'>
											<option value="">--Select Allowance Type--</option>
											@foreach ($allowances as $allowance)
												<option value="{{ $allowance->id }}" {{ old('allowance') == $allowance->id ? 'selected' : '' }}>
													{{ $allowance->name }}</option>
											@endforeach
										</select>
										<input type="number" class="form-control form-control" name="amount" id="amount" min="1"
											value="{{ old('amount') }}" placeholder="Amount">
										<button data-repeater-delete type="button" class="btn btn-icon btn-rounded btn-danger ">
											<i class="ti-trash"></i>
										</button>
									</div>
								</div>
							</div>
						</form>
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
