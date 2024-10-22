<div class="modal fade" id="importPayrollSSSMatrixContribution" tabindex="-1" role="dialog" aria-labelledby="importSSSMatrixContribution"
	aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="importPayrollSSSMatrixContributionlabel">Import SSS Matrix Contribution</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='{{url('import-sss-matrix-contributions')}}' onsubmit='show()' enctype="multipart/form-data" onsubmit="btnImportSSSMatrixContribution.disabled = true; return true;">
				@csrf
				<div class="modal-body">
					<div class="row">
                        <div class="col-lg-12 form-group">
							<label>Upload Excel</label>
                            <input type="file" name='file' class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button id="btnImportSSSMatrixContribution" type="submit" class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>