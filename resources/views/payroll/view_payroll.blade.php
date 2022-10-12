<div class="modal fade" id="view_payroll{{$date_from}}" tabindex="-1" role="dialog" aria-labelledby="view_payrolldata" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="view_payrolldata">View Payroll</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
       
          <div class="modal-body">
            <div class="table-responsive">
                  <table class="table table-hover table-bordered tablewithSearch">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Employee Code</th>
                            <th>Bank Account Number</th>
                            <th>Bank</th>
                            <th>Position</th>
                            <th>Employee Status</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Location</th>
                            <th>Date Hired</th>
                            <th>Monthly Pay</th>
                            <th>Semi Montly Pay</th>
                            <th>Daily Pay</th>
                            <th>Gross Pay</th>
                            <th>Taxable</th>
                            <th>TOTAL DEDUCTIOn</th>
                            <th>NET PAY</th>
                            <th>NET PAY</th>
                            <th>Witholding Tax</th>
                            <th>Absences</th>
                            <th>Late</th>
                            <th>Undertime</th>
                            <th>Total Adjustment Hours</th>
                            <th>Salary Adjustment</th>
                            <th>Overtime</th>
                            <th>Meal Allowance</th>
                            <th>Salary Allowance</th>
                            <th>Out of Town Allowance</th>
                            <th>Incentive Allowance</th>
                            <th>Rellocation Allowance</th>
                            <th>Disc Allowance</th>
                            <th>Transportation Allowance</th>
                            <th>Load Allowance</th>
                            <th>Sick Leave</th>
                            <th>Vacation Leave</th>
                            <th>Work From Home</th>
                            <th>Official Business</th>
                            <th>Sick Leave No Pay</th>
                            <th>Vacation Leave No Pay</th>
                            <th>SSS Reg EE</th>
                            <th>SSS MPF EE</th>
                            <th>PHIC EE</th>
                            <th>HDMF EE</th>
                            <th>HDMF SALARY LOAN</th>
                            <th>SSS SALARY LOAN</th>
                            <th>SSS CALAMITY LOAN</th>
                            <th>SALARY Non-TAX</th>
                            <th>SALARY LOAN</th>
                            <th>COMPANY LOAN</th>
                            <th>OTHERS</th>
                            <th>SSS REG ER</th>
                            <th>SSS MPF ER</th>
                            <th>SSS EC</th>
                            <th>PHIC ER</th>
                            <th>HDMF ER</th>
                            <th>PAYROLL STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($payroll_employees->where('date_from',$payroll->date_from) as $employee)
                      <tr>
                        <td>Name</td>
                        <td>Employee Code</td>
                        <td>Bank Account Number</td>
                        <td>Bank</td>
                        <td>Position</td>
                        <td>Employee Status</td>
                        <td>Company</td>
                        <td>Department</td>
                        <td>Location</td>
                        <td>Date Hired</td>
                        <td>Monthly Pay</td>
                        <td>Semi Montly Pay</td>
                        <td>Daily Pay</td>
                        <td>Gross Pay</td>
                        <td>Taxable</td>
                        <td>TOTAL DEDUCTIOn</td>
                        <td>NET PAY</td>
                        <td>NET PAY</td>
                        <td>Witholding Tax</td>
                        <td>Absences</td>
                        <td>Late</td>
                        <td>Undertime</td>
                        <td>Total Adjustment Hours</td>
                        <td>Salary Adjustment</td>
                        <td>Overtime</td>
                        <td>Meal Allowance</td>
                        <td>Salary Allowance</td>
                        <td>Out of Town Allowance</td>
                        <td>Incentive Allowance</td>
                        <td>Rellocation Allowance</td>
                        <td>Disc Allowance</td>
                        <td>Transportation Allowance</th>
                        <td>Load Allowance</td>
                        <td>Sick Leave</td>
                        <td>Vacation Leave</td>
                        <td>Work From Home</td>
                        <td>Official Business</td>
                        <td>Sick Leave No Pay</td>
                        <td>Vacation Leave No Pay</td>
                        <td>SSS Reg EE</td>
                        <td>SSS MPF EE</td>
                        <td>PHIC EE</td>
                        <td>HDMF EE</td>
                        <td>HDMF SALARY LOAN</td>
                        <td>SSS SALARY LOAN</td>
                        <td>SSS CALAMITY LOAN</td>
                        <td>SALARY Non-TAX</td>
                        <td>SALARY LOAN</td>
                        <td>COMPANY LOAN</td>
                        <td>OTHERS</td>
                        <td>SSS REG ER</td>
                        <td>SSS MPF ER</td>
                        <td>SSS EC</td>
                        <td>PHIC ER</td>
                        <td>HDMF ER</td>
                        <td>PAYROLL STATUS</td>
                    </tr>
                      @endforeach
                      
                    </tbody>
                  </table>
                </div>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
      </div>
    </div>
  </div>