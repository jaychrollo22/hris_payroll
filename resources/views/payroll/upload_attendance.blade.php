<div class="modal fade" id="timekeepingID" tabindex="-1" role="dialog" aria-labelledby="timekeepingdata" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="timekeepingdata">Upload Attendance</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method='POST' action='upload-attendance' onsubmit='show()' enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class='col-md-12 form-group'>
                 Upload File
                <input type="file" name='file' class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
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