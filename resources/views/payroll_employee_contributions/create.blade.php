<div class="modal fade" id="newPayrollEmployeeContribution" tabindex="-1" role="dialog" aria-labelledby="newPayrollEmployeeContributionlabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newPayrollEmployeeContributionlabel">New Employee Contribution</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action='store-payroll-employee-contribution' method="POST">
            @csrf
            <div class="modal-body">
                
                <div class="form-group">
                    <select id="user_id" data-placeholder="Employee" class="form-control form-control-sm required js-example-basic-single " style='width:100%;' name='user_id' required>
                        <option value="">--Select Employee--</option>
                        @foreach($employees as $employee)
                        <option value="{{$employee->user_id}}">{{$employee->first_name}} - {{$employee->last_name}}</option>
                        @endforeach
                    </select>
                  </div>

                <!-- SSS REG EE -->
                <div class="form-group">
                    <label for="sss_reg_ee">SSS REG EE (Amount)</label>
                    <input type="number" class="form-control" id="sss_reg_ee" name="sss_reg_ee" step="0.01">
                </div>

                <!-- SSS MPF EE -->
                <div class="form-group">
                    <label for="sss_mpf_ee">SSS MPF EE (Amount)</label>
                    <input type="number" class="form-control" id="sss_mpf_ee" name="sss_mpf_ee" step="0.01">
                </div>

                <!-- PHIC EE -->
                <div class="form-group">
                    <label for="phic_ee">PHIC EE (Amount)</label>
                    <input type="number" class="form-control" id="phic_ee" name="phic_ee" step="0.01">
                </div>

                <!-- HDMF EE -->
                <div class="form-group">
                    <label for="hdmf_ee">HDMF EE (Amount)</label>
                    <input type="number" class="form-control" id="hdmf_ee" name="hdmf_ee" step="0.01">
                </div>

                <!-- SSS REG ER -->
                <div class="form-group">
                    <label for="sss_reg_er">SSS REG ER (Amount)</label>
                    <input type="number" class="form-control" id="sss_reg_er" name="sss_reg_er" step="0.01">
                </div>

                <!-- SSS MPF ER -->
                <div class="form-group">
                    <label for="sss_mpf_er">SSS MPF ER (Amount)</label>
                    <input type="number" class="form-control" id="sss_mpf_er" name="sss_mpf_er" step="0.01">
                </div>

                <!-- SSS EC -->
                <div class="form-group">
                    <label for="sss_ec">SSS EC (Amount)</label>
                    <input type="number" class="form-control" id="sss_ec" name="sss_ec" step="0.01">
                </div>

                <!-- PHIC ER -->
                <div class="form-group">
                    <label for="phic_er">PHIC ER (Amount)</label>
                    <input type="number" class="form-control" id="phic_er" name="phic_er" step="0.01">
                </div>

                <!-- HDMF ER -->
                <div class="form-group">
                    <label for="hdmf_er">HDMF ER (Amount)</label>
                    <input type="number" class="form-control" id="hdmf_er" name="hdmf_er" step="0.01">
                </div>

                <!-- Payment Schedule -->
                <div class="form-group">
                    <label for="payment_schedule">Payment Schedule</label>
                    <select class="form-control" id="payment_schedule" name="payment_schedule" required>
                        <option value="First Cut-Off">First Cut-Off</option>
                        <option value="Second Cut-Off">Second Cut-Off</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
      </div>
    </div>
  </div>