<div class="modal fade" id="edit_allowance{{ $allowance->id }}" tabindex="-1" role="dialog"
	aria-labelledby="editAllowanceslabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editAllowancelabel">Edit Alowance</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='edit-allowance/{{ $allowance->id }}' onsubmit='show()'>
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class='col-md-12 form-group'>
							Allowance name:
							<input type="text" name='allowance_name' class="form-control" value="{{ $allowance->name }}" required>
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
