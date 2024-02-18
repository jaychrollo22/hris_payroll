<div class="modal fade" id="edit_leave_type{{$leave->id}}" tabindex="-1" role="dialog" aria-labelledby="editLeaveTypelabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editLeaveTypelabel">Edit Leave Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method='POST' action='update-leave-type/{{$leave->id}}' onsubmit='show()'>
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class='col-md-12 form-group'>
                Leave Type
                <input type="text" name='leave_type' class="form-control" placeholder="Leave Type Description" required value="{{ $leave->leave_type }}">
              </div>
              <div class='col-md-12 form-group'>
                Code  
                <input type="text" name='code' class="form-control" placeholder="Leave Type Code" required value="{{ $leave->code }}">
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