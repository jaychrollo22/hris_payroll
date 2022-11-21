<!-- Modal -->
<div class="modal fade" id="view_leave{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="editleaveslabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editleaveslabel">View Leave</h5>
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
              @foreach($all_approvers as $approvers)
                {{$approvers->approver_info->name}}<br>
              @endforeach
            </div>
          </div>
          <div class="form-group row">
            <label for="leave_type" class="col-sm-2 col-form-label">Leave Type</label>
              <div class="col-sm-4">
                {{-- <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Email"> --}}
                <select class="js-example-basic-single w-100 form-control"  id="leave_type" style='width:100%;' name='leave_type' disabled>
                  @foreach ($leave_types as $leave_type)
                    <option value="{{ $leave_type->id }}"{{ $leave_type->id == $leave->leave_type ? 'selected' : ''}}>
                            {{ $leave_type->leave_type }}
                    </option>
                @endforeach                  
                </select>
              </div>
              <div class='col-sm-5'>
                <div class='row'>
                  <div class='col-md-6'>
                    <label class="form-check-label ">
                        @if($leave->withpay == 1)
                            <input type="checkbox" name="withpay" class="form-check-input" value="1" checked disabled>  
                        @else
                            <input type="checkbox" name="withpay" class="form-check-input" value="1" disabled>  
                        @endif
                      With Pay
                  </label>
                  </div>
                  <div class='col-md-6'>
                    <label class="form-check-label ">
                      @if($leave->halfday == 1)
                      <input type="checkbox" name="halfday" class="form-check-input" value="1" checked disabled>  
                  @else
                      <input type="checkbox" name="halfday" class="form-check-input" value="1" disabled>  
                  @endif
                      Halfday
                  </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class='col-md-2'>
                 Date From 
              </div>
              <div class='col-md-4'>
                <input type="date" name='date_from' class="form-control" value="{{$leave->date_from}}" disabled>
              </div>
              <div class='col-md-2'>
                 Date To 
              </div>
              <div class='col-md-4'>
                <input type="date" name='date_to' class="form-control" value="{{$leave->date_to}}" disabled>
              </div>
            </div>
            <div class="form-group row">
              <div class='col-md-2'>
                 Reason
              </div>
              <div class='col-md-10'>
                <textarea  name='reason' class="form-control" value="{{$leave->reason}}" rows='4' disabled>{{$leave->reason}}</textarea>
              </div>
            
            </div>
            <div class="form-group row">
              <div class='col-md-2'>
                 Attachment
          </div>                     
              <div class='col-md-10'>                     
                <a href="{{url($leave->attachment)}}" target='_blank' class="text-start"><button type="button" class="btn btn-outline-info btn-fw ">View Attachment</button></a>
            </div>            
            </div>
          </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
