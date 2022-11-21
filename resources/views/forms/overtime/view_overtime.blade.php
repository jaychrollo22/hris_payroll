<!-- Modal -->
<div class="modal fade" id="view_overtime{{ $overtime->id }}" tabindex="-1" role="dialog" aria-labelledby="viewOt" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewOt">View Overtime</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>    
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
                <input type="date" name='ot_date' value="{{$overtime->ot_date}}" class="form-control" min='{{date('Y-m-d', strtotime("-3 days"))}}' disabled>
              </div>
            </div>
             
            <div class="form-group row">
              <div class='col-md-2'>
                 Start Time
              </div>
              <div class='col-md-4'>
                <input type="time" name='start_time' class="form-control" value="{{ date('H:i', strtotime($overtime->start_time)) }}" disabled>
              </div>
              <div class='col-md-2'>
                 End Time
              </div>
              <div class='col-md-4'>
                <input type="time" name='end_time' class="form-control" value="{{ date('H:i', strtotime($overtime->end_time)) }}" disabled>
              </div>
            </div>
            <div class="form-group row">
              <div class='col-md-2'>
                 Remarks
              </div>
              <div class='col-md-10'>
                <textarea  name='remarks' class="form-control" rows='4' disabled>{{$overtime->remarks}}</textarea>
              </div>
            
            </div>
            <div class="form-group row">
              <div class='col-md-2'>
                 Attachment
              </div>
              <div class='col-md-10'>
                <a href="{{url($overtime->attachment)}}" target='_blank'><button type="button" class="btn btn-outline-info btn-fw ">View Attachment</button></a>
            </div>          
            </div>
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>    
      </div>
    </div>
  </div>