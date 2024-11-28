<div class="modal fade" id="addPayrollRegisterRemarks{{$payroll->id}}" tabindex="-1" role="dialog" aria-labelledby="addPayrollRegisterRemarks"
	aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addPayrollRegisterRemarkslabel">
					Add Payroll Remarks for {{ $payroll->name }} <br>
					<small>{{ $payroll_period_detail ? $payroll_period_detail->payroll_name : "" }}</small>
				</h5>
				
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='add-payroll-remarks/{{$payroll->id}}' onsubmit='show()' enctype="multipart/form-data" onsubmit="btnaddPayrollRegisterRemarks.disabled = true; return true;">
				@csrf
				<div class="modal-body">
					
					<input type="hidden" name="payroll_period" value="{{$payroll_period}}">
					<input type="hidden" name="company" value="{{$company}}">
					<input type="hidden" name="department" value="{{$department}}">
					<div class="row">
                        <div class="col-lg-12 form-group">
							<label>Remarks</label>
                            <textarea name="remarks" cols="30" rows="5" class="form-control" placeholder="Input Remarks"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button id="btnaddPayrollRegisterRemarks" type="submit" class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
