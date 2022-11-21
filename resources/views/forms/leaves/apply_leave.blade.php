<!-- Modal -->
<div class="modal fade" id="applyLeave" tabindex="-1" role="dialog" aria-labelledby="applyLeaveData" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="applyLeaveData">Apply Leave</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	<form method='POST' action='new-leave' onsubmit='show()'  enctype="multipart/form-data">
				@csrf       
      <div class="modal-body">
        <div class="form-group row">
          <div class='col-md-2'>
            Approver 
          </div>
          <div class='col-md-9'>
            @foreach($all_approvers as $approvers)
              {{$approvers->approver_info->name}}<br>
            @endforeach
          </div>
        </div>
        <div class="form-group row">
          <label for="leave_type" class="col-sm-2 col-form-label">Leave Type</label>
            <div class="col-sm-4">
              {{-- <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Email"> --}}
              <select class="js-example-basic-single w-100 form-control"  id="leave_type" style='width:100%;' name='leave_type' required>
                <option value="">--Select--</option>
                @foreach($leave_types as $leave_type)
                  <option value="{{$leave_type->id}}">{{$leave_type->leave_type}}</option>
                @endforeach
              </select>
            </div>
            <div class='col-sm-5'>
              <div class='row'>
                <div class='col-md-6'>
                  <label class="form-check-label ">
                    <input type="checkbox" name="withpay" class="form-check-input" value="1">
                    With Pay
                </label>
                </div>
                <div class='col-md-6'>
                  <label class="form-check-label ">
                    <input type="checkbox" name="halfday" class="form-check-input" value="1">
                    Halfday
                </label>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Date From 
            </div>
            <div class='col-md-4'>
              <input type="date" name='date_from' class="form-control" required>
            </div>
            <div class='col-md-2'>
               Date To 
            </div>
            <div class='col-md-4'>
              <input type="date" name='date_to' class="form-control" required>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Reason
            </div>
            <div class='col-md-10'>
              <textarea  name='reason' class="form-control" rows='4' required></textarea>
            </div>
          
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Attachment
            </div>
            <div class='col-md-10'>
              <input type="file" name="attachment" class="form-control"  placeholder="Upload Supporting Documents" multiple>
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