<!-- Modal -->
<div class="modal fade" id="requestToCancelLeave{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="applyLeaveData" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
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
                    <div class='col-md-12'>
                        Reason to Cancel Leave
                      <textarea name='request_to_cancel_remarks' class="form-control" rows='4' disabled>{{$leave->request_to_cancel_remarks}}</textarea>
                    </div>
                  </div>
                </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                <a href="/approve-request-to-cancel-leave/{{$leave->id}}" class="btn btn-success">Approve</a> 
                <a href="/decline-request-to-cancel-leave/{{$leave->id}}" class="btn btn-danger">Decline</a> 
                
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
