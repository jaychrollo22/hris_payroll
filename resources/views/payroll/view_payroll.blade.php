<div class="modal fade" id="view_payroll{{$payroll->date_from}}" tabindex="-1" role="dialog" aria-labelledby="view_payrolldata" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="view_payrolldata">View Payroll {{date('M d, Y',strtotime($payroll->date_from))}} - {{date('M d, Y',strtotime($payroll->date_to))}}</h5>
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
                <th>Witholding Tax</th>
                <th>Absences</th>
                <th>Late</th>
                <th>Undertime</th>
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
                <td>{{$employee->name}}</td>
                <td>{{$employee->emp_code}}</td>
                <td>{{$employee->bank_acctno}}</td>
                <td>{{$employee->bank}}</td>
                <td>{{$employee->position}}</td>
                <td>{{$employee->department}}</td>
                <td>{{$employee->location}}</td>
                <td>{{date("M d, Y",strtotime($employee->date_hired))}}</td>
                <td>{{number_format($employee->month_pay,2)}}</td>
                <td>{{number_format($employee->semi_month_pay,2)}}</td>
                <td>{{number_format($employee->daily_pay,2)}}</td>
                <td>{{number_format($employee->gross_pay,2)}}</td>
                <td>{{number_format($employee->total_taxable,2)}}</td>
                <td>{{number_format($employee->total_deduction,2)}}</td>
                <td>{{number_format($employee->netpay,2)}}</td>
                <td>{{number_format($employee->witholding_tax,2)}}</td>
                <td>{{number_format($employee->absences,2)}}</td>
                <td>{{number_format($employee->late,2)}}</td>
                <td>{{number_format($employee->undertime,2)}}</td>
                <td>{{number_format($employee->salary_adjustment,2)}}</td>
                <td>{{number_format($employee->overtime,2)}}</td>
                <td>{{number_format($employee->meal_allowance,2)}}</td>
                <td>{{number_format($employee->salary_allowance,2)}}</td>
                <td>{{number_format($employee->oot_allowance,2)}}</td>
                <td>{{number_format($employee->inc_allowance,2)}}</td>
                <td>{{number_format($employee->rel_allowance,2)}}</td>
                <td>{{number_format($employee->disc_allowance,2)}}</td>
                <td>{{number_format($employee->trans_allowance,2)}}</th>
                <td>{{number_format($employee->load_allowance,2)}}</td>
                <td>{{number_format($employee->sick_leave,2)}}</td>
                <td>{{number_format($employee->vacation_leave,2)}}</td>
                <td>{{number_format($employee->wfhome,2)}}</td>
                <td>{{number_format($employee->offbusiness,2)}}</td>
                <td>{{number_format($employee->sick_leave_nopay,2)}}</td>
                <td>{{number_format($employee->vacation_leave_nopay,2)}}</td>
                <td>{{number_format($employee->sss_regee,2)}}</td>
                <td>{{number_format($employee->sss_mpfee,2)}}</td>
                <td>{{number_format($employee->phic_ee,2)}}</td>
                <td>{{number_format($employee->hdmf_ee,2)}}</td>
                <td>{{number_format($employee->hdmf_sal_loan,2)}}</td>
                <td>{{number_format($employee->hdmf_cal_loan,2)}}</td>
                <td>{{number_format($employee->sss_sal_loan,2)}}</td>
                <td>{{number_format($employee->sss_cal_loan,2)}}</td>
                <td>{{number_format($employee->sal_loan,2)}}</td>
                <td>{{number_format($employee->com_loan,2)}}</td>
                <td>{{number_format($employee->others,2)}}</td>
                <td>{{number_format($employee->sss_reg_er,2)}}</td>
                <td>{{number_format($employee->sss_mpf_er,2)}}</td>
                <td>{{number_format($employee->sss_ec,2)}}</td>
                <td>{{number_format($employee->phic_er,2)}}</td>
                <td>{{number_format($employee->hdmf_er,2)}}</td>
                <td>{{$employee->payroll_status}}</td>
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