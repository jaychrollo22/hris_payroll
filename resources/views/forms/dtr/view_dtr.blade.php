<!-- Modal -->
<div class="modal fade" id="view_dtr{{ $dtr->id }}" tabindex="-1" role="dialog" aria-labelledby="viewdtrslabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewdtrslabel">View DTR Correction</h5>
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
                <input type="date" name='dtr_date' class="form-control" min='{{date('Y-m-d', strtotime("-3 days"))}}' value="{{ $dtr->dtr_date }}" disabled>
              </div>
              <div class='col-md-2'>
                DTR Type
             </div>
             <div class='col-md-4'>
              <select class="form-control"  id="crction" name='correction' onchange="viewDtr(this,{{$dtr->id}})" disabled>
                <option value="Both" {{ $dtr->correction == 'Both' ? 'selected' : ''}}>Both Time-In and Time-Out</option>                                    
                <option value="Time-in" {{ $dtr->correction == 'Time-in' ? 'selected' : ''}}>Time-in Only</option>
                <option value="Time-out" {{ $dtr->correction == 'Time-out' ? 'selected' : ''}}>Time-out Only</option>
            </select>
             </div>            
            </div>           
            <div class="form-group row" >
                  <div class='col-md-2'>
                    Time-In
                  </div>
                  <div class='col-md-4'>
                    <input type="time" name='time_in'  class="form-control" value="{{ isset($dtr->time_in) ? date('H:i', strtotime($dtr->time_in)) : '' }}" disabled>
                  </div>
                  <div class='col-md-2'>
                    Time-out
                  </div>
                  <div class='col-md-4'>
                    <input type="time" name='time_out' class="form-control" value="{{ isset($dtr->time_out) ? date('H:i', strtotime($dtr->time_out)) : '' }}" disabled>
                  </div>
            </div>
            <div class="form-group row">
              <div class='col-md-2'>
                 Reason
              </div>
              <div class='col-md-10'>
                <textarea  name='remarks' class="form-control" rows='4' disabled>{{ $dtr->remarks }}</textarea>
              </div>
            
            </div>
            <div class="form-group row">
              <div class='col-md-2'>
                 Attachment
              </div>
              <div class='col-md-10'>
                <a href="{{url($dtr->attachment)}}" target='_blank'><button type="button" class="btn btn-outline-info btn-fw ">View Attachment</button></a>
            </div>
            </div>
          </div>
  
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" {{ (auth()->user()->employee->immediate_sup_data != null) ? "" : 'disabled'}}>Save</button>
        </div>     
      </div>
    </div>
  </div>