<div class="modal fade" id="view_wfh{{ $wfh->id }}" tabindex="-1" role="dialog" aria-labelledby="editWFHslabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editWFHslabel">Edit WFH</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>      
        <div class="modal-body text-right">
          <div class="form-group row">
            <div class='col-md-2'>
              Approver 
            </div>
            <div class='col-md-10 text-left'>
              @foreach($all_approvers as $approvers)
              {{$approvers->approver_info->name}}<br>
            @endforeach
            </div>
          </div>
            <div class="form-group row">
              <div class='align-self-center col-md-2 text-right'>
                Date
              </div>
              <div class='col-md-4'>
                <input type="date" name='applied_date' value="{{$wfh->applied_date}}" class="form-control" disabled>
              </div>
            </div>
            <div class="form-group row">
              <div class='align-self-center col-md-2 text-right'>
                Time In
              </div>
              <div class='col-md-4'>
                <input type="time" name='date_from' value="{{$wfh->date_from}}" class="form-control" disabled>
              </div>
              <div class='align-self-center col-md-2 text-right'>
                Time Out
              </div>
              <div class='col-md-4'>
                <input type="time" name='date_to' value="{{$wfh->date_to}}" class="form-control" disabled>
              </div>
            </div>
            <div class="form-group row">
              <div class='col-md-2'>
                 Remarks
              </div>
              <div class='col-md-10'>
                <textarea  name='remarks' class="form-control" rows='4' disabled>{{$wfh->remarks}}</textarea>
              </div>
            </div>
            <div class="form-group row">
                <div class='col-md-2'>
                   Attachment
                </div>
                <div class='col-md-2'>
                  @if($wfh->attachment)
                    <a href="{{url($wfh->attachment)}}" target='_blank'><button type="button" class="btn btn-outline-info btn-fw ">View Attachment</button></a>
                  @endif
              </div>          
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>    
      </div>
    </div>
  </div>
