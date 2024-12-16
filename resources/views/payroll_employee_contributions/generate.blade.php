<!-- Modal -->
<div class="modal fade" id="generatePayrollEmployeeContribution" tabindex="-1" role="dialog" aria-labelledby="generatePayrollRegister" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="generateEmployeeContribution">Generate Employee Contribution</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method='POST' action='generate-employee-contribution' onsubmit="btnDtr.disabled = true; return true;"  enctype="multipart/form-data">
            @csrf      
        <div class="modal-body">
            <div class=row>
                <div class='col-md-12'>
                  <div class="form-group">
                      <label for="payment_schedule">Payment Schedule</label>
                      <select class="form-control" id="payment_schedule" name="payment_schedule" required>
                          <option value="First Cut-Off">First Cut-Off</option>
                          <option value="Second Cut-Off">Second Cut-Off</option>
                      </select>
                  </div>
                </div>
                <div class='col-md-12'>
                    <div class="form-group">
                    <label for="payroll_register">Company:</label>
                    <select data-placeholder="Select Company" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='company' required>
                        <option value="">-- Select Company --</option>
                        @foreach($companies as $comp)
                        <option value="{{$comp->id}}" @if ($comp->id == $company) selected @endif>{{$comp->company_name}} - {{$comp->company_code}}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </div>
        </div>
  
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button name="btnDtr" type="submit" class="btn btn-primary">Generate</button>
        </div>
      </form>      
      </div>
    </div>
  </div>