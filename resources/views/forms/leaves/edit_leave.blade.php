  
  @extends('layouts.header')
  @section('content')
  <div class="main-panel">
    <div class="content-wrapper">
      <div class='row'>
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Leave</h4>
                    <div class="col-md-12">

                      <form method='POST' action='{{url('edit-leave/' . $leave->id)}}' onsubmit='show()' enctype="multipart/form-data">
                        @csrf       
                        
                        <div class="modal-body">
                          <div class="form-group row"> 
                            <div class='col-md-2'>
                              Approver 
                            </div>
                            <div class='col-md-9'>
                              @if(count($all_approvers) > 0)
                                @foreach($all_approvers as $approvers)
                                  {{$approvers->approver_info->name}}<br>
                                @endforeach
                              @endif
                            </div>
                          </div>

                            <div class="form-group row">
                              <label for="leave_type" class="col-sm-2 col-form-label">Leave Type</label>
                                <div class="col-sm-4">
                                  <select class="form-control" id="leave_type" style='width:100%;' name='leave_type' required>
                                    @foreach($employee_leave_type_balance as $leave_balance)
                                      @php
                                        $used_leave = checkUsedLeave(auth()->user()->id,$leave_balance->leave_type_info->id,$leave_balance->year);
                                        $total_balance = $leave_balance->total_balance;
                                        $remaining = $leave_balance->total_balance - $used_leave;
                                      @endphp

                                      <option value="{{$leave_balance->leave_type_info->id}}" data-balance="{{$remaining}}" {{ $leave_balance->leave_type_info->id == $leave->leave_type ? 'selected' : ''}}>{{$leave_balance->leave_type_info->leave_type}}</option>
                                    @endforeach                
                                  </select>
                                </div>
                                <div class='col-sm-5'>
                                  <div class='row'>
                                    <div class='col-md-6'>
                                      <label class="form-check-label ">
                                        <input type="hidden" id="leave_balances" name="leave_balances" value="{{$remaining}}">
                                        <div>
                                          <label class="form-check-label ">
                                            @if($leave->withpay == 1)
                                              <input type="checkbox" name="withpay" class="form-check-input" :disabled="isAllowedWithPay" checked>  
                                            @else
                                                <input type="checkbox" name="withpay" class="form-check-input" :disabled="isAllowedWithPay">  
                                            @endif
                                              With Pay
                                        </label>
                                        </div>
                                    </label>
                                    </div>
                                    <div class='col-md-6'>
                                      <label class="form-check-label ">
                                            <input id="editViewleaveHalfday" type="checkbox" name="halfday" class="form-check-input" value="1" {{ $leave->halfday == 1 ? 'checked' : "" }}>  
                                        Halfday
                                    </label>

                                    <br>
                                      @if($leave->halfday == 1)
                                        <div class="edithalfDayStatus">
                                          <select id="halfday_status" name="halfday_status" class="form-control" value="{{$leave->halfday_status}}">
                                              <option value="">Choose One</option>
                                              <option value="First Shift" {{ $leave->halfday_status == 'First Shift' ? 'selected' : ''}}>First Shift</option>
                                              <option value="Second Shift" {{ $leave->halfday_status == 'Second Shift' ? 'selected' : ''}}>Second Shift</option>
                                          </select>
                                        </div>
                                      @else
                                      <div class="edithalfDayStatus">
                                        <select id="halfday_status" name="halfday_status" class="form-control">
                                            <option value="">Choose One</option>
                                            <option value="First Shift">First Shift</option>
                                            <option value="Second Shift">Second Shift</option>
                                        </select>
                                      </div>
                                      @endif
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="form-group row">
                              <div class='col-md-2'>
                                Date From 
                              </div>
                              <div class='col-md-4'>
                                <input type="date" name='date_from' class="form-control" value="{{$leave->date_from}}" required>
                              </div>
                              <div class='col-md-2'>
                                Date To 
                              </div>
                              <div class='col-md-4'>
                                <input id="dateToLeave" type="date" name='date_to' class="form-control" value="{{$leave->date_to}}" required>
                              </div>
                            </div>
                            <div class="form-group row">
                              <div class='col-md-2'>
                                Reason
                              </div>
                              <div class='col-md-10'>
                                <textarea  name='reason' class="form-control"  rows='4' required>{{$leave->reason}}</textarea>
                              </div>
                            
                            </div>
                            <div class="form-group row">
                              <div class='col-md-2'>
                                Attachment
                              </div>                     
                              <div class='col-md-10'>                     
                                    <input type="file" name="attachment" class="form-control" placeholder="Upload Supporting Documents">  
                              </div>            
                            </div>
                          </div>
                          
                          <div class="modal-footer">
                              @if($leave->attachment)
                                <a href="{{url($leave->attachment)}}" target='_blank'><button type="button" class="btn btn-outline-info btn-fw ">View Attachment</button></a>
                              @endif
                              <a href="/file-leave" type="button" class="btn btn-secondary">Close</a>
                              <button type="submit" class="btn btn-primary">Save</button>
                          </div>
                        </div>
                      </form>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('leave_type').addEventListener('change', function() {
    var selectedOption = this.options[this.selectedIndex];
    var balanceValue = selectedOption.getAttribute('data-balance');
    if (balanceValue !== null) {
      var checkbox = document.getElementById('withPayCheckBox');
      if(Number(balanceValue) > 0){
        checkbox.disabled = false;
      }else{
        checkbox.disabled = true;
        var leave_balances = document.getElementById('leave_balances');
      }
    }
  });
</script>

<script>
  var halfdayCheck = document.getElementById('editViewleaveHalfday');
  var dateTo = document.getElementById('dateToLeave');
  var halfday_status = document.getElementById('halfday_status');

  function updatehalfdayCheck() {
        if(halfdayCheck.checked) {
            dateTo.disabled = true;
            halfday_status.setAttribute('required', true); 
        } else {
            dateTo.disabled = false;
            halfday_status.removeAttribute('required');
        }
    }
    halfdayCheck.addEventListener('change', updatehalfdayCheck);
    window.onload = updatehalfdayCheck;
</script>

@endsection