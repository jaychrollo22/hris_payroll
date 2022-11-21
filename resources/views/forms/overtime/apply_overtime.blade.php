<div class="modal fade" id="applyovertime" tabindex="-1" role="dialog" aria-labelledby="otData" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="otData">Apply Overtime</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
			<form method='POST' action='new-overtime' onsubmit='show()'  enctype="multipart/form-data">
				@csrf      
      <div class="modal-body">
        <div class="form-group row">
          <div class='col-md-2'>
            Approver 
          </div>
          <div class='col-md-9'>
            <div class='col-md-9'>
              @foreach($all_approvers as $approvers)
                {{$approvers->approver_info->name}}<br>
              @endforeach
            </div>
          </div>
          
        </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Date
            </div>
            <div class='col-md-4'>
              <input type="date" name='ot_date' class="form-control" min='{{date('Y-m-d', strtotime("-3 days"))}}' required>
            </div>
          </div>
           
          <div class="form-group row">
            <div class='col-md-2'>
               Start Time
            </div>
            <div class='col-md-4'>
              <input type="time" name='start_time' class="form-control" required>
            </div>
            <div class='col-md-2'>
               End Time
            </div>
            <div class='col-md-4'>
              <input type="time" name='end_time' class="form-control" required>
            </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Remarks
            </div>
            <div class='col-md-10'>
              <textarea  name='remarks' class="form-control" rows='4' required></textarea>
            </div>
          
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Attachment
            </div>
            <div class='col-md-10'>
              <input type="file" name="attachment" class="form-control"  placeholder="Upload Supporting Documents">
            </div>
          
          </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" {{ (auth()->user()->employee->immediate_sup_data != null) ? "" : 'disabled'}}>Save</button>
      </div>
    </form>      
    </div>
  </div>
</div>