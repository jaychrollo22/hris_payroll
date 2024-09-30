<div class="modal fade" id="newPayrollPeriod" tabindex="-1" role="dialog" aria-labelledby="newPayrollPeriodlabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newPayrollPeriodlabel">New Payroll Period</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method='POST' action='store-payroll-period' onsubmit='show()' >
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class='col-md-12 form-group'>
                 Payroll Name  
                 <input type="text" name="payroll_name" class="form-control" id="payroll_name" required>
              </div>
            </div>
            <div class="row">
              <div class='col-md-6 form-group'>
                Start Date  
                <input type="date" name="start_date" class="form-control" id="start_date" required>
              </div>
              <div class='col-md-6 form-group'>
                End Date  
                <input type="date" name="end_date" class="form-control" id="end_date" required>
              </div>
            </div>
            <div class="row">
              <div class='col-md-6 form-group'>
                Payroll Frequency
                <select name="payroll_frequency" id="payroll_frequency" class="form-control" required>
                    <option value="Cutoff ">Weekly</option>
                    <option value="bi-weekly">Bi-Weekly</option>
                    <option value="semi-monthly">Semi-Monthly</option>
                    <option value="monthly">Monthly</option>
                </select>
              </div>
              <div class='col-md-6 form-group'>
                Cut-off Date
                <input type="date" name="cut_off_date" id="cut_off_date" class="form-control" required>
              </div>
            </div>
            <div class="row">
                <div class='col-md-6 form-group'>
                  Payment Date  
                  <input type="date" name="payment_date" id="payment_date" class="form-control" required>
                </div>
                <div class='col-md-6 form-group'>
                  Total Days 
                  <input type="number" name="total_days" id="total_days" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class='col-md-6 form-group'>
                  Payroll Cut-Off
                  <div class="form-group">
                    <select data-placeholder="Select Payroll Cutoff" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='payroll_cutoff' id="payroll_cutoff"  required>
                        <option value="">-- Select Payroll Cutoff --</option>
                        <option value="First Cut-Off" {{$payroll_cutoff == 'First Cut-Off' ? 'selected' : ""}}>First Cut-Off</option>
                        <option value="Second Cut-Off" {{$payroll_cutoff == 'Second Cut-Off' ? 'selected' : ""}}>Second Cut-Off</option>
                    </select>
                  </div>
                </div>
                <div class='col-md-6 form-group'>
                  Status
                  <select name="status" id="status" class="form-control" required>
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                        <option value="in_progress">In Progress</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class='col-md-12 form-group'>
                  Notes
                  <textarea name="notes" id="notes" class="form-control"></textarea>
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