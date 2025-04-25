<div class="modal fade" id="newConsultant" tabindex="-1" role="dialog" aria-labelledby="newConsultantlabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newConsultantlabel">New Consultant</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='new-consultant' onsubmit='show()'>
				@csrf
				<div class="modal-body">
					<div class="row">
                        <div class='col-md-12'>
                          <div class="form-group">
                                <select data-placeholder="Select Consultant" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='consultant' required>
                                <option value="">-- Select Consultant --</option>
								@foreach($consultants as $con)
								<option value="{{$con->user_id}}">{{ $con->first_name .' ' .$con->last_name }}</option>
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