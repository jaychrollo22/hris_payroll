<!-- Modal -->
<div class="modal fade" id="edit_leave{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="editleaveslabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editleaveslabel">Update Leave</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method='POST' action='edit-leave/{{ $leave->id }}' onsubmit='show()' enctype="multipart/form-data">
          @csrf       

          <div class="modal-body">
            <div class="form-group row"> 
              <div class='col-md-2'>
                Approver 
              </div>
              <div class='col-md-9'>
                @if(count($all_approvers) > 0)
                  @foreach($all_approvers as $approvers)
                    {{$approvers->approver_info->name}}<br>
                  @endforeach
                @endif
              </div>
            </div>

              <div class="form-group row">
                <label for="leave_type" class="col-sm-2 col-form-label">Leave Type</label>
                  <div class="col-sm-4">
                    <select class="js-example-basic-single w-100 form-control"  id="leave_type" style='width:100%;' name='leave_type' required>
                      @foreach ($leave_types as $leave_type)
                        <option value="{{ $leave_type->id }}"{{ $leave_type->id == $leave->leave_type ? 'selected' : ''}}>
                                {{ $leave_type->leave_type }}
                        </option>
                      @endforeach                  
                    </select>
                  </div>
                  <div class='col-sm-5'>
                    <div class='row'>
                      <div class='col-md-6'>
                        <label class="form-check-label ">
                            @if($leave->withpay == 1)
                                <input type="checkbox" name="withpay" class="form-check-input" value="1" checked>  
                            @else
                                <input type="checkbox" name="withpay" class="form-check-input" value="1">  
                            @endif
                          With Pay
                      </label>
                      </div>
                      <div class='col-md-6'>
                        <label class="form-check-label ">
                          @if($leave->halfday == 1)
                              <input id="editViewleaveHalfday" type="checkbox" name="halfday" class="form-check-input" value="1" checked>  
                          @else
                              <input id="editViewleaveHalfday" type="checkbox" name="halfday" class="form-check-input" value="1">  
                          @endif
                          Halfday
                      </label>

                      <br>
                        @if($leave->halfday == 1)
                          <div class="edithalfDayStatus">
                            <select name="halfday_status" class="form-control" value="{{$leave->halfday_status}}">
                                <option value="">Choose One</option>
                                <option value="First Shift" {{ $leave->halfday_status == 'First Shift' ? 'selected' : ''}}>First Shift</option>
                                <option value="Second Shift" {{ $leave->halfday_status == 'Second Shift' ? 'selected' : ''}}>Second Shift</option>
                            </select>
                          </div>
                        @else
                        <div class="edithalfDayStatus">
                          <select name="halfday_status" class="form-control">
                              <option value="">Choose One</option>
                              <option value="First Shift">First Shift</option>
                              <option value="Second Shift">Second Shift</option>
                          </select>
                        </div>
                        @endif
                      </div>
                    </div>
                  </div>
              </div>
              <div class="form-group row">
                <div class='col-md-2'>
                  Date From 
                </div>
                <div class='col-md-4'>
                  <input type="date" name='date_from' class="form-control" value="{{$leave->date_from}}" required>
                </div>
                <div class='col-md-2'>
                  Date To 
                </div>
                <div class='col-md-4'>
                  <input type="date" name='date_to' class="form-control" value="{{$leave->date_to}}" required>
                </div>
              </div>
              <div class="form-group row">
                <div class='col-md-2'>
                  Reason
                </div>
                <div class='col-md-10'>
                  <textarea  name='reason' class="form-control"  rows='4' required>{{$leave->reason}}</textarea>
                </div>
              
              </div>
              <div class="form-group row">
                <div class='col-md-2'>
                  Attachment
                </div>                     
                <div class='col-md-10'>                     
                      <input type="file" name="attachment" class="form-control" placeholder="Upload Supporting Documents">  
                </div>            
              </div>
            </div>
            
            <div class="modal-footer">
                @if($leave->attachment)
                  <a href="{{url($leave->attachment)}}" target='_blank'><button type="button" class="btn btn-outline-info btn-fw ">View Attachment</button></a>
                @endif
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </div>
        </form>
    </div>
  </div>
</div>
