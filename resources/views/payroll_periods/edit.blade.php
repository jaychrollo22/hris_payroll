<div class="modal fade" id="edit_payroll_period{{$payrollPeriod->id}}" tabindex="-1" role="dialog" aria-labelledby="EditPayrollPeriodData" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='row'>
                    <div class='col-md-12'>
                        <h5 class="modal-title" id="EditPayrollPeriodData">Edit Payroll Period</h5>
                    </div>
                </div>
            </div>
            <form  method='POST' action='update-payroll-period/{{$payrollPeriod->id}}' onsubmit='show()' >
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row">
                      <div class='col-md-12 form-group'>
                        Payroll Name  
                        <input type="text" name="payroll_name" class="form-control" id="payroll_name" value="{{ $payrollPeriod->payroll_name }}" required>
                      </div>
                    </div>
                    <div class="row">
                      <div class='col-md-6 form-group'>
                        Start Date  
                        <input type="date" name="start_date" class="form-control" id="start_date" value="{{ $payrollPeriod->start_date }}" required>
                      </div>
                      <div class='col-md-6 form-group'>
                        End Date  
                        <input type="date" name="end_date" class="form-control" id="end_date" value="{{ $payrollPeriod->end_date }}" required>
                      </div>
                    </div>
                    <div class="row">
                      <div class='col-md-6 form-group'>
                        Payroll Frequency
                        <select name="payroll_frequency" id="payroll_frequency" class="form-control" required>
                            <option value="weekly" {{ $payrollPeriod->payroll_frequency == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="bi-weekly" {{ $payrollPeriod->payroll_frequency == 'bi-weekly' ? 'selected' : '' }}>Bi-Weekly</option>
                            <option value="semi-monthly" {{ $payrollPeriod->payroll_frequency == 'semi-monthly' ? 'selected' : '' }}>Semi-Monthly</option>
                            <option value="monthly" {{ $payrollPeriod->payroll_frequency == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                      </div>
                      <div class='col-md-6 form-group'>
                        Cut-off Date
                        <input type="date" name="cut_off_date" id="cut_off_date" class="form-control" value="{{ $payrollPeriod->cut_off_date }}" required>
                      </div>
                    </div>
                    <div class="row">
                        <div class='col-md-6 form-group'>
                          Payment Date  
                          <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ $payrollPeriod->payment_date }}" required>
                        </div>
                        <div class='col-md-6 form-group'>
                          Total Days 
                          <input type="number" name="total_days" id="total_days" class="form-control" value="{{ $payrollPeriod->total_days }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-md-6 form-group'>
                          Status
                          <select name="status" id="status" class="form-control" required>
                                <option value="open" {{ $payrollPeriod->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ $payrollPeriod->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="in_progress" {{ $payrollPeriod->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                          </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-md-12 form-group'>
                          Notes
                          <textarea name="notes" id="notes" class="form-control">{{ $payrollPeriod->notes }}</textarea>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                  </div>
            </form>
        </div>
    </div>
</div>