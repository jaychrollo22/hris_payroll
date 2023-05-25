<!-- Modal -->
<div class="modal fade" id="edit_overtime{{ $overtime->id }}" tabindex="-1" role="dialog" aria-labelledby="editOTslabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editOTslabel">Edit Overtime</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


      <form method='POST' action='edit-overtime/{{ $overtime->id }}' onsubmit='show()' enctype="multipart/form-data">
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
              <input type="date" name='ot_date' value="{{$overtime->ot_date}}" class="form-control" required>
            </div>
          </div>
           
          <div class="form-group row">
            <div class='col-md-2'>
               Start Time
            </div>
            <div class='col-md-4'>
              <input type="time" name='start_time' class="form-control" value="{{ date('H:i', strtotime($overtime->start_time)) }}" required>
            </div>
            <div class='col-md-2'>
               End Time
            </div>
            <div class='col-md-4'>
              <input type="time" name='end_time' class="form-control" value="{{ date('H:i', strtotime($overtime->end_time)) }}" required>
            </div>
          </div>

          <div class="form-group row">
            <div class='col-md-2'>
               Break (Hrs)
            </div>
            <div class='col-md-4'>
              <input type="number" step="0.01" min="0" max="3" name='break_hrs' class="form-control" placeholder="0.00" value="{{$overtime->break_hrs}}">
            </div>
          </div>

          <div class="form-group row">
            <div class='col-md-2'>
               Remarks
            </div>
            <div class='col-md-10'>
              <textarea  name='remarks' class="form-control" rows='4' required>{{$overtime->remarks}}</textarea>
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
        
        @if($overtime->attachment)
          <a href="{{url($overtime->attachment)}}" target='_blank'><button type="button" class="btn btn-outline-info btn-fw ">View Attachment</button></a>
        @endif
        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" {{ (auth()->user()->employee->immediate_sup_data != null) ? "" : 'disabled'}}>Save</button>

      </div>
    </form>      
    </div>
  </div>
</div>