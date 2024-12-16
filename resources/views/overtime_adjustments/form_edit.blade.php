<div class="modal fade" id="editOvertimeAdjustment{{$overtime_adjustment->id}}" tabindex="-1" role="dialog" aria-labelledby="editSalaryAdjustmentLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newHolidaylabel">Edit Payroll Overtime Adjusment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method='POST' action='edit-payroll-overtime-adjustment/{{$overtime_adjustment->id}}' onsubmit='show()' >
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class='col-md-12 form-group'>
                Employee
                <select data-placeholder="Select Employee" class="form-control form-control-sm js-example-basic-single " style='width:100%;' name='employee' required>
                    <option value="">--Select Employee--</option>
                    @foreach($employees as $employee)
                        <option value="{{$employee->user_id}}" @if ($overtime_adjustment->user_id == $employee->user_id) selected @endif>{{$employee->first_name .' '.$employee->last_name }}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="row">
              <div class='col-md-12 form-group'>
                Payroll Period
                <select data-placeholder="Select Payroll Period" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='payroll_period' required>
                    <option value="">-- Select Payroll Period --</option>
                    @foreach($payroll_periods as $payroll_period_item)
                    <option value="{{$payroll_period_item->id}}" @if ($payroll_period_item->id == $overtime_adjustment->payroll_period_id) selected @endif>{{$payroll_period_item->payroll_name}} ({{$payroll_period_item->start_date .'-'. $payroll_period_item->end_date}})</option>
                    @endforeach
                </select>
              </div>
            </div>
            {{-- <div class="row">
              <div class='col-md-12 form-group'>
                Payroll Cut Off
                <select data-placeholder="Select Payroll Cut Off" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='payroll_cutoff' id="type"  required>
                  <option value="">-- Select Payroll Cut Off --</option>
                  <option value="First Cut-Off" {{$overtime_adjustment->payroll_cutoff == 'First Cut-Off' ? 'selected' : ""}}>First Cut-Off</option>
                  <option value="Second Cut-Off" {{$overtime_adjustment->payroll_cutoff == 'Second Cut-Off' ? 'selected' : ""}}>Second Cut-Off</option>
                  <option value="Every Cut-Off" {{$overtime_adjustment->payroll_cutoff == 'Every Cut-Off' ? 'selected' : ""}}>Every Cut-Off</option>
                </select>
              </div>
            </div> --}}
            <div class="row">
              <div class='col-md-12 form-group'>
                Amount
                <input type="number" class="form-control form-control-sm" name="amount" id="amount"  required min="1" placeholder="0.00" value="{{$overtime_adjustment->amount}}">
              </div>
            </div>
            <div class="row">
              <div class='col-md-12 form-group'>
                Type
                <div class="form-group">
                  <select data-placeholder="Select Status" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='type' id="type"  required>
                      <option value="">-- Select Status --</option>
                      <option value="Addition" {{$overtime_adjustment->type == 'Addition' ? 'selected' : ""}}>Addition</option>
                      <option value="Deduction" {{$overtime_adjustment->type == 'Deduction' ? 'selected' : ""}}>Deduction</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class='col-md-12 form-group'>
                Reason
                <input type="text" class="form-control form-control-sm" name="reason" id="reason"  required value="{{$overtime_adjustment->reason}}">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form> 
      </div>
    </div>
  </div>