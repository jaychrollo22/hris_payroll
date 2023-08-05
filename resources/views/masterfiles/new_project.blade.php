<div class="modal fade" id="newProject" tabindex="-1" role="dialog" aria-labelledby="newCompanylabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newProjectlabel">New Project</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='store-project' onsubmit='show()' enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class='col-md-12 form-group'>
							Project ID:
							<input type="text" name='project_id' class="form-control" required>
						</div>
						<div class='col-md-12 form-group'>
							Project Title:
							<input type="text" name='project_title' class="form-control" required>
						</div>
						<div class='col-md-12 form-group'>
							Company:
							<select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company_id'>
                                <option value="">-- Select Company --</option>
                                @foreach($companies as $comp)
                                <option value="{{$comp->id}}">{{$comp->company_name}} - {{$comp->company_code}}</option>
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
