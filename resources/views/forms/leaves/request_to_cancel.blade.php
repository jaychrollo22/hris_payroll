<!-- Modal -->
<div class="modal fade" id="requestToCancelLeave{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="applyLeaveData" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="applyLeaveData">Request to Cancel Leave</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div id="app">
          <form method='POST' action='request-to-cancel-leave/{{ $leave->id }}'  enctype="multipart/form-data">
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
                    <div class='col-md-2'>
                      Reason to Cancel Leave
                    </div>
                    <div class='col-md-10'>
                      <textarea  name='request_to_cancel_remarks' class="form-control" rows='4' required>{{$leave->request_to_cancel_remarks}}</textarea>
                    </div>
                  </div>
                </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                @if($leave->request_to_cancel == '1')
                    <a href="/void-to-cancel-leave/{{$leave->id}}" class="btn btn-danger">Void to Cancel</a> 
                @elseif($leave->request_to_cancel == '0')
                    <button disabled name="btnLeave" class="btn btn-warning">Request to Cancel has been Declined</button>
                @elseif($leave->request_to_cancel == '2')
                    <button disabled name="btnLeave" class="btn btn-success">Request to Cancel has been Approved</button>
                @else
                    <button type="submit" name="btnLeave" class="btn btn-primary">Save</button>
                @endif   
                
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
