<div class="modal fade" id="newPagibigContribution" tabindex="-1" role="dialog" aria-labelledby="newPagibigContributionlabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newPagibigContributionlabel">New Pagibig Contribution</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='new-pagibig-contribution' onsubmit='show()'>
				@csrf
				<div class="modal-body">
					<div class="row">
                        <div class='col-md-12'>
                            <div class="form-group">
                            Employee name:
                            <select data-placeholder="Select Employee" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='user_id' required>
                                <option value="">-- Select Employee --</option>
                                @foreach($employees as $employee)
                                <option value="{{$employee->user_id}}">{{ $employee->first_name .' ' .$employee->last_name }}</option>
                                @endforeach
                            </select>
                            </div>
					    </div>
                        <div class="col-lg-12 form-group">
							<label for="amount">Amount</label>
							<input type="number" class="form-control form-control-sm" name="amount" id="amount" step="0.01" min="1" value="{{ old('amount') }}" placeholder="0.00" required>
						</div>
                        <div class='col-md-12'>
                            <div class="form-group">
                                Payroll Cut-Off:
                                <select data-placeholder="Select Payroll Cut-Off" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='payroll_cutoff' required>
                                    <option value="">-- Select Payroll Cut-off --</option>
                                    <option value="First Cut-Off">First Cut-Off</option>
                                    <option value="Second Cut-Off">Second Cut-Off</option>
                                    <option value="Every Cut-Off">Every Cut-Off</option>
                                </select>
                            </div>
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