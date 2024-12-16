<div class="modal fade" id="newDeduction" tabindex="-1" role="dialog" aria-labelledby="newDeductionlabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newDeductionlabel">New Deduction</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='new-deduction' onsubmit='show()'>
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class='col-md-12 form-group'>
							Deduction name:
							<input type="text" name='deduction_name' class="form-control" required>
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
