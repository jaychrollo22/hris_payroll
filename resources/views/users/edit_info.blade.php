<div class="modal fade" id="editInfo" tabindex="-1" role="dialog" aria-labelledby="editInfoLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editInfoLabel">Edit Information</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='updateInfo/{{ auth()->user()->employee->id }}' onsubmit='show()' enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<div class="form-card">
						<h2 class="fs-title">Personal Information</h2>
						<div class='row mb-2'>
							<div class='col-md-3'>
								First Name
								<input type="text" name="first_name" value="{{ auth()->user()->employee->first_name }}" class='form-control form-control-sm required' placeholder="First Name"
									required />
							</div>
							<div class='col-md-3'>
								Middle Name
								<input type="text" name="middle_name" value="{{ auth()->user()->employee->middle_name }}" class='form-control form-control-sm ' placeholder="Middle Name" />
							</div>
							<div class='col-md-2'>
								Middle Initial
								<input type="text" name="middile_initial" value="{{ auth()->user()->employee->middle_initial }}" class='form-control form-control-sm '
									placeholder="Middle Initial" />
							</div>
							<div class='col-md-4'>
								Last Name
								<input type="text" name="last_name" value="{{ auth()->user()->employee->last_name }}" class='form-control form-control-sm required' placeholder="Last Name"
									required />
							</div>

						</div>
						<div class='row mb-2'>
							<div class='col-md-3'>
								Suffix
								<input type="text" name="suffix" class='form-control form-control-sm ' value="{{ auth()->user()->employee->suffix }}" placeholder="Suffix" />
							</div>
							<div class='col-md-3'>
								Nickname
								<input type="text" name="nickname" class='form-control form-control-sm required' value="{{ auth()->user()->employee->nick_name }}" placeholder="Nickname"
									required />
							</div>
							<div class='col-md-2'>
								Marital Status
								<select class='form-control required form-control-sm ' name='marital_status' required>
									<option value=''>--Select Marital Status--</option>
									@foreach ($marital_statuses as $marital_status)
										<option value='{{ ucfirst(strtolower($marital_status->name))  }}' @if (auth()->user()->employee->marital_status == ucfirst(strtolower($marital_status->name))) selected @endif>{{ $marital_status->name }}</option>
									@endforeach
								</select>
							</div>
							<div class='col-md-2'>
								Religion
								<input type="text" name="religion" value="{{ auth()->user()->employee->religion }}" class='form-control form-control-sm required' placeholder="Religion" />
							</div>
							<div class='col-md-2'>
								Gender
								<select data-placeholder="Gender" class="form-control form-control-sm required" name='gender'>
									<option value="">--Select Gender--</option>
									<option value="MALE" @if (auth()->user()->employee->gender == "MALE") selected @endif>MALE</option>
									<option value="FEMALE" @if (auth()->user()->employee->gender == "FEMALE") selected @endif>FEMALE</option>
								</select>
							</div>
						</div>

						<hr>
						<h2 class="fs-title">Birth Information</h2>
						<div class="row mb-2">
							<div class="col-md-3">
								Birth date
								<input type="date" name="birthdate" class='form-control form-control-sm required'
									max='{{ date('Y-m-d', strtotime('-18 year')) }}' value="{{ auth()->user()->employee->birth_date }}" placeholder="BirthDate" />
							</div>

							<div class="col-md-3">
								Birth Place
								<input type="text" name="birthplace" value="{{ auth()->user()->employee->birth_place }}" class='form-control form-control-sm required'placeholder="Manila" />
							</div>
						</div>
						<hr>
						<h2 class="fs-title">Contact Details</h2>
						<div class='row mb-2'>
							<div class='col-md-4'>
								Personal Email
								<input type="email" name="personal_email" value="{{ auth()->user()->employee->personal_email }}" class='form-control form-control-sm required'
									placeholder="Personal Email" />
							</div>
							<div class='col-md-4'>
								Personal Contact Number
								<input type="number" name="personal_number" value="{{ auth()->user()->employee->personal_number }}" class='form-control form-control-sm required'
									placeholder="Personal Contact Number" />
							</div>
						</div>
						<div class='row mb-2'>
							<div class='col-md-6'>
								Current Address
								<input type="text" id="currentAdd" name="present_address" value="{{ auth()->user()->employee->present_address }}" class='form-control form-control-sm required'
									placeholder="Current Address" />
							</div>
							<div class='col-md-6'>
								<input onclick='same_as_current(this.value)' type="checkbox" class="ml-1 form-check-input" id="same_as">
								<label class='ml-4 mb-0' for='same_as'><small><i>Same as Current Address</i></small></label>
								<input type="text" id='permanent_address' name="permanent_address"
									class='form-control form-control-sm required' value="{{ auth()->user()->employee->permanent_address }}" placeholder="Permanent Address" required />
							</div>
						</div>
						<hr>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
			</form>
		</div>
	</div>
</div>
<script>
function same_as_current(value)
  {
     var checkbox = document.querySelector('#same_as');
     var getCurrentAdd = document.querySelector('#currentAdd')
     if(checkbox.checked == true)
     {
      document.getElementById("permanent_address").hidden = true;
      document.getElementById("permanent_address").required = false;
      document.getElementById("permanent_address").value = "";
      document.getElementById("permanent_address").classList.remove("required");
      document.getElementById("permanent_address").style.border = '1px solid #CED4DA';    
      

     }
     else
     {
      document.getElementById("permanent_address").hidden = false;
      document.getElementById("permanent_address").required = true;
      document.getElementById("permanent_address").value = "";
      document.getElementById("permanent_address").classList.add("required");
     }
   
    
 }

  function add_approver()
  {
    var lastItemID = $('.approvers-data').children().last().attr('id');
    var last_id = lastItemID.split("_");
        finalLastId = parseInt(last_id[1]) + 1;
                                 
        var item = "<div class='row mb-2  mt-2 ' id='approver_"+finalLastId+"'>";
            item+= "<div class='col-md-1  align-self-center'>";
            item+= "<small class='align-items-center'>"+finalLastId+"</small>";
            item+= "</div>";
            item+= " <div class='col-md-11'>";
            item+= " <select data-placeholder='Approver' class='form-control form-control-sm required js-example-basic-single' style='width:100%;' name='approver[]' required>";
            item+= "<option value=''>-- Approver --</option>";
            item+= " @foreach($users as $user)";
            item+= "<option value='{{$user->id}}'>{{$user->name}}</option>";
            item+= "@endforeach";
            item+= "</select>";
            item+= "</div>";
            item+= "</div>";
          
            $(".approvers-data").append(item);
            $(".js-example-basic-single").select2();

  }
  function remove_approver()
  {
    if($('div.approvers-data div.row').length > 1)
    {
    var lastItemID = $('.approvers-data').children().last().attr('id');
    $('#'+lastItemID).remove();
    }

  }
</script>