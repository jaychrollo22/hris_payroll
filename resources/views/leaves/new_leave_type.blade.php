<div class="modal fade" id="newLeaveType" tabindex="-1" role="dialog" aria-labelledby="newLeaveTypelabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newLeaveTypelabel">New Leave Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form  method='POST' action='new-leave-type' onsubmit='show()' >
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class='col-md-12 form-group'>
              Leave Type
              <input type="text" name='leave_type' class="form-control" placeholder="Leave Type Description" required>
            </div>
            <div class='col-md-12 form-group'>
              Code  
              <input type="text" name='code' class="form-control" placeholder="Leave Type Code" required>
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