<div class="modal fade" id="newCostCenter" tabindex="-1" role="dialog" aria-labelledby="newCostCenterlabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="newEarlyCutofflabel">New Cost Center</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='store-cost-center' onsubmit='show()' enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class='col-md-12 form-group'>
							Code
							<input type="text" name="code" class="form-control">
						</div>
						<div class='col-md-12 form-group'>
							Name
							<input type="text" name="name" class="form-control">
						</div>
						<div class='col-md-12 form-group'>
							Company
							<input type="text" name="company_code" class="form-control">
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
