<!-- Modal -->
<div class="modal fade" id="edit_leave{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="editleaveslabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editleaveslabel">Update Leave</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div id="app{{$leave->id}}">
          <form method='POST' action='edit-leave/{{ $leave->id }}' onsubmit='show()' enctype="multipart/form-data">
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
                      <select v-on:change="validateLeave" v-model="leave_type" class="form-control"  id="leave_type" style='width:100%;' name='leave_type' required>
                        @foreach ($leave_types as $leave_type)
                          @if($leave_type->code == 'VL')
                            <option value="{{$leave_type->id}}" {{ $leave_type->id == $leave->leave_type ? 'selected' : ''}}>{{$leave_type->leave_type}}</option>
                          @elseif($leave_type->code == 'SL')
                            <option value="{{$leave_type->id}}" {{ $leave_type->id == $leave->leave_type ? 'selected' : ''}}>{{$leave_type->leave_type}}</option>
                          @elseif($is_allowed_to_file_sil && $leave_type->code == 'SIL' && $employee_status->classifcation == 'Project Based')
                            <option value="{{$leave_type->id}}" {{ $leave_type->id == $leave->leave_type ? 'selected' : ''}}>{{$leave_type->leave_type}}</option>
                          @elseif($is_allowed_to_file_ml && $leave_type->code == 'ML')
                            <option value="{{$leave_type->id}}" {{ $leave_type->id == $leave->leave_type ? 'selected' : ''}}>{{$leave_type->leave_type}}</option>
                          @elseif($is_allowed_to_file_pl && $leave_type->code == 'PL')
                            <option value="{{$leave_type->id}}" {{ $leave_type->id == $leave->leave_type ? 'selected' : ''}}>{{$leave_type->leave_type}}</option>
                          @elseif($is_allowed_to_file_spl && $leave_type->code == 'SPL')
                            <option value="{{$leave_type->id}}" {{ $leave_type->id == $leave->leave_type ? 'selected' : ''}}>{{$leave_type->leave_type}}</option>
                          @elseif($is_allowed_to_file_splw && $leave_type->code == 'SPLW')
                            <option value="{{$leave_type->id}}" {{ $leave_type->id == $leave->leave_type ? 'selected' : ''}}>{{$leave_type->leave_type}}</option>
                          @elseif($is_allowed_to_file_splvv && $leave_type->code == 'SPLVV')
                            <option value="{{$leave_type->id}}" {{ $leave_type->id == $leave->leave_type ? 'selected' : ''}}>{{$leave_type->leave_type}}</option>
                          @endif
                        @endforeach                  
                      </select>
                    </div>
                    <div class='col-sm-5'>
                      <div class='row'>
                        <div class='col-md-6'>
                          <label class="form-check-label ">
                            <input type="hidden" v-model="leave_balances" name="leave_balances" :value="leave_balances">
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
                            @if($leave->halfday == 1)
                                <input id="editViewleaveHalfday" type="checkbox" name="halfday" class="form-check-input" value="1" checked>  
                            @else
                                <input id="editViewleaveHalfday" type="checkbox" name="halfday" class="form-check-input" value="0">  
                            @endif
                            Halfday
                        </label>

                        <br>
                          @if($leave->halfday == 1)
                            <div class="edithalfDayStatus">
                              <select name="halfday_status" class="form-control" value="{{$leave->halfday_status}}">
                                  <option value="">Choose One</option>
                                  <option value="First Shift" {{ $leave->halfday_status == 'First Shift' ? 'selected' : ''}}>First Shift</option>
                                  <option value="Second Shift" {{ $leave->halfday_status == 'Second Shift' ? 'selected' : ''}}>Second Shift</option>
                              </select>
                            </div>
                          @else
                          <div class="edithalfDayStatus">
                            <select name="halfday_status" class="form-control">
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
                    <input type="date" name='date_to' class="form-control" value="{{$leave->date_to}}" required>
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
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </form>
        </div>
    </div>
  </div>
</div>
<script>
  var app = new Vue({
          el: '#app' + '<?php echo $leave->id; ?>',
          data : {
              leave_balances : '',
              leave_type : '<?php echo $leave->leave_type; ?>',
              isAllowedWithPay : true,
              vl_balance : '<?php echo $vl_balance; ?>',
              sl_balance : '<?php echo $sl_balance; ?>',
              ml_balance : '<?php echo $ml_balance; ?>',
              pl_balance : '<?php echo $pl_balance; ?>',
              spl_balance : '<?php echo $spl_balance; ?>',
              splw_balance : '<?php echo $splw_balance; ?>',
              splvv_balance : '<?php echo $splvv_balance; ?>',
          },
          created () {
            this.validateLeave();
          },
          methods: {
            validateLeave() {
              this.leave_balances = '';
              if(this.leave_type == '1'){ // Vacation Leave
                  if(Number(this.vl_balance) > 0){
                    this.leave_balances = this.vl_balance;
                    this.isAllowedWithPay = false;
                  }else{
                    this.isAllowedWithPay = true;
                  }
              }
              else if(this.leave_type == '2'){ // Sick Leave
                  if(Number(this.sl_balance) > 0){
                    this.leave_balances = this.sl_balance;
                    this.isAllowedWithPay = false;
                  }else{
                    this.isAllowedWithPay = true;
                  }
              }
              else if(this.leave_type == '3'){ // Maternity Leave
                  if(Number(this.ml_balance) > 0){
                    this.leave_balances = this.ml_balance;
                    this.isAllowedWithPay = false;
                  }else{
                    this.isAllowedWithPay = true;
                  }
              }
              else if(this.leave_type == '4'){ // Paternity Leave
                  if(Number(this.pl_balance) > 0){
                    this.leave_balances = this.pl_balance;
                    this.isAllowedWithPay = false;
                  }else{
                    this.isAllowedWithPay = true;
                  }
              }
              else if(this.leave_type == '5'){ // SPL
                  if(Number(this.spl_balance) > 0){
                    this.leave_balances = this.spl_balance;
                    this.isAllowedWithPay = false;
                  }else{
                    this.isAllowedWithPay = true;
                  }
              }
              else if(this.leave_type == '7'){ // SPLW
                  if(Number(this.splw_balance) > 0){
                    this.leave_balances = this.splw_balance;
                    this.isAllowedWithPay = false;
                  }else{
                    this.isAllowedWithPay = true;
                  }
              }
              else if(this.leave_type == '9'){ // SPLVV
                  if(Number(this.splvv_balance) > 0){
                    this.leave_balances = this.splvv_balance;
                    this.isAllowedWithPay = false;
                  }else{
                    this.isAllowedWithPay = true;
                  }
              }
            }
          },
  });
</script>
