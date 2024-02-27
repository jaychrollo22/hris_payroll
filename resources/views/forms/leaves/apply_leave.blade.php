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
      <div id="app">
        <form method='POST' action='new-leave' onsubmit="btnLeave.disabled = true; return true;"  enctype="multipart/form-data">
              @csrf       
            <div class="modal-body">
              <div class="app">
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
                      <select class="form-control" id="leave_type" style='width:100%;' name='leave_type' required>
                        <option value="">--Select--</option>
                        @foreach($employee_leave_type_balance as $leave_type)
                          @php
                            $used_leave = checkUsedLeave(auth()->user()->id,$leave_balance->leave_type_info->id,$leave_balance->year);
                            $total_balance = $leave_type->total_balance;
                            $remaining = $leave_type->total_balance - $used_leave;
                          @endphp

                          <option value="{{$leave_type->leave_type_info->id}}" data-balance="{{$leave_type->total_balance}}">{{$leave_type->leave_type_info->leave_type}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class='col-sm-5'>
                      <div class='row'>
                        <div class='col-md-6'>
                          <input type="hidden" id="leave_balances" name="leave_balances" value="">
                          <div>
                            <label class="form-check-label ">
                              <input type="checkbox" name="withpay" class="form-check-input" id="withPayCheckBox" disabled>
                              With Pay
                          </label>
                          </div>
                  
                        </div>
                        <div class='col-md-6'>
                          <label class="form-check-label ">
                            <input id="leaveHalfday" type="checkbox" name="halfday" class="form-check-input" value="1">
                            Halfday
                            <br>
                            <div class="halfDayStatus">
                                <select id="halfday_status" name="halfday_status" class="form-control">
                                    <option value="">Choose One</option>
                                    <option value="First Shift">First Shift</option>
                                    <option value="Second Shift">Second Shift</option>
                                </select>
                            </div>
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
                      <input id="dateToLeave" type="date" name='date_to' class="form-control" required>
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
              </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" name="btnLeave" class="btn btn-primary">Save</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>