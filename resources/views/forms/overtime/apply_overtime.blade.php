<!-- Modal -->
<div class="modal fade" id="applyLeave" tabindex="-1" role="dialog" aria-labelledby="applyLeaveData" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="applyLeaveData">Apply Overtime</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <div class='col-md-2'>
            Approver : 
          </div>
          <div class='col-md-9'>
            {{auth()->user()->employee->immediate_sup_data->name}}
          </div>
          
        </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Date
            </div>
            <div class='col-md-4'>
              <input type="date" name='date_from' class="form-control" min='{{date('Y-m-d')}}' requried>
            </div>
              <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="membershipRadios" id="membershipRadios1" value="" >
                      Beforeshift OT
                    <i class="input-helper"></i></label>
                  </div>
              </div>
              <div class="col-sm-2">
                <div class="form-check">
                  <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="membershipRadios" id="membershipRadios2" checked="" value="option2">
                    Aftershift OT
                  <i class="input-helper"></i></label>
                </div>
              </div>
          </div>
           
          <div class="form-group row">
            <div class='col-md-2'>
               Start Time
            </div>
            <div class='col-md-4'>
              <input type="time" name='start_time' class="form-control" requried>
            </div>
            <div class='col-md-2'>
               End Time
            </div>
            <div class='col-md-4'>
              <input type="time" name='end_time' class="form-control" requried>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Remarks
            </div>
            <div class='col-md-10'>
              <textarea  name='reason' class="form-control" rows='4' requried></textarea>
            </div>
          
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Attachment
            </div>
            <div class='col-md-10'>
              <input type="file" class="form-control"  placeholder="Upload Supporting Documents" multiple>
            </div>
          
          </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>