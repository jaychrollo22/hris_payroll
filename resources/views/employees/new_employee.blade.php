<!-- Modal -->
<div class="modal fade" id="newEmployee" tabindex="-1" role="dialog" aria-labelledby="newEmployeeData" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newEmployeeData">New Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form  method='POST' action='new-biocode' onsubmit='show()' >
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class='col-md-3 form-group'>
                Employee Code
                </div>
                <div class='col-md-9 form-group'>
                    <input type="text" name='emp_code' class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class='col-md-3 form-group'>
                First Name
                </div>
                <div class='col-md-9 form-group'>
                    <input type="text" name='first_name' class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class='col-md-3 form-group'>
                Last Name
                </div>
                <div class='col-md-9 form-group'>
                    <input type="text" name='last_name' class="form-control" required>
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

