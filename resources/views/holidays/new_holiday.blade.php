<div class="modal fade" id="newHoliday" tabindex="-1" role="dialog" aria-labelledby="newHolidaylabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newHolidaylabel">New Holiday</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form  method='POST' action='new-holiday' onsubmit='show()' >
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class='col-md-12 form-group'>
               Holiday name  
              <input type="text" name='holiday_name' class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class='col-md-12 form-group'>
               Date  
              <input type="date" name='date' class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class='col-md-12 form-group'>
               Type  
               <select class='form-control' name='holiday_type' required>
                  <option ></option>
                  <option value = 'Regular Holiday'>Legal Holiday</option>
                  <option value = 'Special Holiday'>Special Holiday</option>
              </select>
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