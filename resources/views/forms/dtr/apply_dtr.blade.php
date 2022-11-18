<!-- Modal -->
<div class="modal fade" id="dtrc" tabindex="-1" role="dialog" aria-labelledby="dtrdata" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dtrdata">Apply DTR Correction</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
			<form method='POST' action='new-dtr' onsubmit='show()'  enctype="multipart/form-data">
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
              <input type="date" name='dtr_date' class="form-control" min='{{date('Y-m-d', strtotime("-3 days"))}}' required>
            </div>
            <div class='col-md-2'>
              DTR Type
           </div>
           <div class='col-md-4'>
            <select class="form-control"  id="correction" name='correction' onchange="AdddtrType(this)" required>
              <option value="Both">Both Time-In and Time-Out</option>                                    
              <option value="Time-in">Time-in Only</option>
              <option value="Time-out">Time-out Only</option>
          </select>
           </div>            
          </div>           
          <div class="form-group row" >
                <div class='col-md-2'>
                  Time-In
                </div>
                <div class='col-md-4'>
                  <input type="time" name='time_in' id="time_in" class="form-control" required>
                </div>
                <div class='col-md-2'>
                  Time-out
                </div>
                <div class='col-md-4'>
                  <input type="time" name='time_out' id="time_out" class="form-control" required>
                </div>
          </div>
          <div class="form-group row">
            <div class='col-md-2'>
               Reason
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
