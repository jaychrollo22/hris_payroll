<!-- Modal -->
<div class="modal fade" id="editEmployee{{$employee->id}}" tabindex="-1" role="dialog" aria-labelledby="editEmployeeData" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editEmployeeData">Edit Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form  method='POST' action='update-biocode' onsubmit='show()' >
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class='col-md-3 form-group'>
                Employee Code
                </div>
                <div class='col-md-9 form-group'>
                    <input type="text" name='emp_code' class="form-control" value='{{$employee->emp_code}}' readonly required>
                </div>
            </div>
            <div class="row">
                <div class='col-md-3 form-group'>
                First Name
                </div>
                <div class='col-md-9 form-group'>
                    <input type="text" name='first_name' class="form-control" value='{{$employee->first_name}}' required>
                </div>
            </div>
            <div class="row">
                <div class='col-md-3 form-group'>
                Last Name
                </div>
                <div class='col-md-9 form-group'>
                    <input type="text" name='last_name' class="form-control" value='{{$employee->last_name}}' required>
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

