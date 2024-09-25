<!-- Modal -->
<div class="modal fade" id="generate_payroll_register" tabindex="-1" role="dialog" aria-labelledby="generatePayrollRegister" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="generatePayrollRegister">Generate Payroll Register</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method='POST' action='generate-payroll-register' onsubmit="btnDtr.disabled = true; return true;"  enctype="multipart/form-data">
            @csrf      
        <div class="modal-body">
            <div class=row>
                <div class='col-md-12'>
                    <div class="form-group">
                    <label for="payroll_register">Payroll Period:</label>
                    <select data-placeholder="Select Payroll Period" class="form-control form-control-sm required js-example-basic-single" style='width:100%;' name='payroll_period' required>
                        <option value="">-- Select Payroll Period --</option>
                        @foreach($payroll_periods as $payroll_period_item)
                        <option value="{{$payroll_period_item->id}}" @if ($payroll_period_item->id == $payroll_period) selected @endif>{{$payroll_period_item->payroll_name}} ({{$payroll_period_item->start_date .'-'. $payroll_period_item->end_date}})</option>
                        @endforeach
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