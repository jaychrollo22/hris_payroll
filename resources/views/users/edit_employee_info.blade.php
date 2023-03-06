<div class="modal fade" id="editEmpInfo" tabindex="-1" role="dialog" aria-labelledby="editEmpInfoLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editEmpInfoLabel">Edit Employee Information</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method='POST' action='updateEmpInfo/{{ auth()->user()->employee->id }}' onsubmit='show()' enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
          <div class="form-card">
            <h2 class="fs-title">Employment Information</h2>
            <div class='row mb-2'>
              <div class='col-md-4'>
                Company
                <select data-placeholder="Company" class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='company' required>
                  <option value="">--Select Company--</option>
                  @foreach($companies as $company)
                    <option value="{{$company->id}}" @if (auth()->user()->employee->company_id == $company->id) selected @endif>{{$company->company_name}} - {{$company->company_code}}</option>
                  @endforeach
                </select>
              </div>
              <div class='col-md-4'>
                Position
                <input type="text" name="position" class='form-control form-control-sm required' placeholder="POSITION" value="{{ auth()->user()->employee->position }}"/>
              </div>
              <div class='col-md-4'>
                Department
                <select data-placeholder="Department" class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='department' required>
                    <option value="">--Select Department--</option>
                    @foreach($departments as $department)
                      <option value="{{$department->id}}" @if (auth()->user()->employee->department_id == $department->id) selected @endif>{{$department->code}} - {{$department->name}}</option>
                    @endforeach
                </select>
              </div>
              <div class='col-md-4'>
                Classification
                <select data-placeholder="Classification" class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='classification' required>
                  <option value="">--Select Classification--</option>
                  @foreach($classifications as $classification)
                    <option value="{{$classification->name}}" @if (auth()->user()->employee->classification == $classification->name) selected @endif>{{$classification->name}}</option>
                  @endforeach
              </select>
              </div>
              <div class='col-md-4'>
                Level
                <select data-placeholder="Level" class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='level' required>
                  <option value="">--Select Level--</option>
                  @foreach($levels as $level)
                    <option value="{{$level->name}}" @if (auth()->user()->employee->level == $level->name) selected @endif>{{$level->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class='col-md-4'>
                Immediate Supervisor
                <select data-placeholder=" Immediate Supervisor" class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='immediate_supervisor' required>
                  <option value="">-- Immediate Supervisor--</option>
                    @foreach($users as $user)
                      <option value="{{$user->id}}" @if (auth()->user()->employee->immediate_sup == $user->id) selected @endif>{{$user->name}}</option>
                    @endforeach
                </select>
              </div>
              <div class='col-md-4'>
                Biometric Code
                <input type="text" name="biometric_code" value="{{ auth()->user()->employee->employee_number }}" class='form-control form-control-sm required' placeholder="BIOMETRIC CODE"/>
              </div>
              <div class='col-md-4'>
                Date Hired
                <input type="date" name="date_hired" value="{{ auth()->user()->employee->original_date_hired }}" class='form-control form-control-sm required' placeholder="Start Date"/>
              </div>
              <div class='col-md-4'>
                Work Email
                <input type="email" name="work_email" value="{{ auth()->user()->email }}" readonly="true" class='form-control form-control-sm required' placeholder="Work Email"/>
              </div>
              <div class='col-md-4'>
                Schedule
                <select data-placeholder="Schedule Period" class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='schedule' required>
                  <option value="">-- Schedule Period --</option>
                  @foreach($schedules as $schedule)
                    <option value="{{$schedule->id}}" @if (auth()->user()->employee->schedule_id == $schedule->id) selected @endif>{{$schedule->schedule_name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <hr>
            <div class='row mb-2'>
              <div class='col-md-4'>
                <h2 class="fs-title">Forms Approver
                <button type="button" onclick='add_approver()' class="btn btn-inverse-success btn-icon btn-sm">
                  <i class="ti-plus"></i>
                </button>
                
                <button type="button"  onclick='remove_approver()' class="btn btn-inverse-danger btn-icon btn-sm">
                  <i class="ti-minus"></i>
                </button>
                </h2>
                <div class='approvers-data'>
                @foreach (auth()->user()->approvers as $approver)
                  <div class='row mb-2  mt-2 ' id='approver_1'>
                    <div class='col-md-1  align-self-center'>
                      <small class='align-items-center'>{{ $approver->level }}</small>
                    </div>
                    <div class='col-md-11'>
                      <select data-placeholder="Approver" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='approver[]' required>
                        <option value="">-- Approver --</option>
                          @foreach($users as $user)
                            <option value="{{$user->id}}" @if($user->id == $approver->approver_id) selected @endif>{{$user->name}}</option>
                          @endforeach
                      </select>
                    </div>
                  </div>
                @endforeach
                  
                </div>
              </div>
              <div class='col-md-1'>
              </div>
              {{-- <div class='col-md-7'>
                <h2 class="fs-title">Payment Information</h2>
                <div class='row'> 
                    <div class='col-md-6'>
                      Payment Period
                      <select data-placeholder="Payment Period" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='payment_period' required>
                        <option value="">-- Payment Period --</option>
                        <option value="Monthly">Monthly</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Daily">Daily</option>
                      </select>
                    </div>
                    <div class='col-md-6'>
                      Payment Type
                      <select data-placeholder="Payment Type" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='payment_type' required>
                        <option value="">-- Payment Type --</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                      </select>
                    </div>
                </div>
                <div class='row'> 
                    <div class='col-md-6'>
                      Bank Name
                      <select data-placeholder="Bank Name" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='bank_name' required>
                        <option value="">-- Bank Name --</option>
                        @foreach($banks as $bank)
                        <option value="{{$bank->bank_name}}">{{$bank->bank_name}} - {{$bank->bank_code}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class='col-md-6'>
                      Account Number
                      <input type="text" name="account_number" class='form-control form-control-sm required' placeholder="Bank Account"/>
                    </div>
                </div>
                <div class='row'> 
                    <div class='col-md-6'>
                      Monthly Rate
                      <input type="text" name="monthy_rate" class='form-control form-control-sm required' placeholder="Monthly Rate"/>
                    </div>
                    <div class='col-md-6'>
                      Daily Rate
                      <input type="text" name="account_number" class='form-control form-control-sm required' placeholder="Daily Rate"/>
                    </div>
                </div>
                </div> --}}
            </div>
            <hr>
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
<script>

  function add_approver()
  {
    var lastItemID = $('.approvers-data').children().last().attr('id');
    console.log(lastItemID);
    if(lastItemID){
        var last_id = lastItemID.split("_");
        finalLastId = parseInt(last_id[1]) + 1;
    }else{
      finalLastId =  1;
    }
   
                                 
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