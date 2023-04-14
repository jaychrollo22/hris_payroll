<div class="modal fade" id="editEmpContactInfo" tabindex="-1" role="dialog" aria-labelledby="editContactInfoLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editInfoLabel">Edit Contact Person</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='updateEmpContactInfo/{{ $user->id }}' onsubmit='show()' enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<div class="form-card">
						<h2 class="fs-title">In case of Emergency</h2>
						<div class='row mb-2'>
							<div class='col-md-12 mt-1'>
								Name
								<input type="text" name="name" value="{{ $user->employee->contact_person ? $user->employee->contact_person->name : ""}}" class='form-control form-control-sm required' placeholder="Name"
									required />
							</div>
							<div class='col-md-12 mt-1'>
                                Contact Number
								<input type="text" name="contact_number" value="{{ $user->employee->contact_person ? $user->employee->contact_person->contact_number : "" }}" class='form-control form-control-sm ' placeholder="Contact Number" required/>
							</div>
							<div class='col-md-12 mt-1'>
								Relationship
								<input type="text" name="relation" value="{{ $user->employee->contact_person ? $user->employee->contact_person->relation : "" }}" class='form-control form-control-sm '
									placeholder="Relationship" required />
							</div>
						</div>
					</div>
                </div>    
                <div class="modal-footer">

					<div class="row">
						<div class="col-md-12">
							<input type="checkbox" id="privacy-contact-check" class="ml-1 form-check-input">
							<label class='ml-4 mb-0' for='same_as'><small><i> I hereby declare that the information provided is true and correct.  I also understand that any willful misrepresentation may result to disciplinary action.</i></small></label>
						</div>
						<div class="col-md-12">
							<input type="checkbox" id="privacy-contact" disabled class="ml-1 form-check-input">
							<label class='ml-4 mb-0' for='same_as'><small><i>By submitting this form, the Employee hereby explicitly and unambiguously consents to the collection, use and transfer, in electronic or other form, of the Employee’s personal data as described in this form and any other materials by and among, as applicable, the Company, its Subsidiaries or Affiliates, and the Employer for the exclusive purpose of implementing, administering and managing the Employee’s employment.</i></small></label>
						</div>
					</div>
					
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit-contact-btn" disabled class="btn btn-primary">Save</button>
                </div>
			</form>
		</div>
	</div>
</div>
