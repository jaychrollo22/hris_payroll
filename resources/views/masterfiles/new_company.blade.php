<div class="modal fade" id="newCompany" tabindex="-1" role="dialog" aria-labelledby="newCompanylabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newCompanylabel">New Company</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='newCompany' onsubmit='show()' enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class='col-md-12 form-group'>
							Company name:
							<input type="text" name='company_name' class="form-control" required>
						</div>
						<div class='col-md-12 form-group'>
							Company code:
							<input type="text" name='company_code' class="form-control" required>
						</div>
						<div class='col-md-12 form-group'>
							Company logo:
							<input type="file" class="form-control" accept="image/*" name="logo_file" id="asd" required>
						</div>
						<div class="col-md-12 form-group">
							Change Icon
							<input type="file" class="form-control" accept="image/*" name="icon_file" id="inputImage" required>
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
