<!DOCTYPE html>
<html>
<head>
    <title>Payroll</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 3px;
            text-align: left;
            font-size: 12px;
            font-family: Arial;
            font-style: normal;
        }
        .section-title {
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

    <table>
        <tr>
            <td><strong>Name:</strong></td>
            <td>{{ $payrollRegister->name }}</td>
            <td><strong>Payroll Period:</strong></td>
            <td>{{ $payrollRegister->payrollPeriod->payroll_name }}</td>
        </tr>
        <tr>
            <td><strong>Employment Status:</strong></td>
            <td>{{ $payrollRegister->employment_status }}</td>
            <td><strong>Cut off Covered:</strong></td>
            <td>{{ $payrollRegister->cut_from .' - '. $payrollRegister->cut_to }}</td>
        </tr>
        <tr>
            <td><strong>Daily Rate:</strong></td>
            <td>{{ $payrollRegister->daily_rate }}</td>
            <td><strong>Location:</strong></td>
            <td>{{ $payrollRegister->employee->location }}</td>
        </tr>
        <tr>
            <td><strong>Bank:</strong></td>
            <td colspan="3">{{ $payrollRegister->bank }}</td>
        </tr>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th class="section-title" colspan="2">INCOME</th>
                <th class="section-title" colspan="2">DEDUCTIONS</th>
            </tr>
        </thead>
        <tbody>
            <!-- Income Section -->
            <tr>
                <td>Basic Pay</td>
                <td>{{ $payrollRegister->basic_pay }}</td>
                <td>Withholding Tax</td>
                <td>{{ $payrollRegister->withholding_tax }}</td>
            </tr>
            <tr>
                <td>Absences</td>
                <td>{{ $payrollRegister->absences_amount }}</td>
                <td>SSS Regular Contribution – EE</td>
                <td>{{ $payrollRegister->sss_reg_ee_15 }}</td>
            </tr>
            <tr>
                <td>Late</td>
                <td>{{ $payrollRegister->lates_amount }}</td>
                <td>SSS MPF Contribution – EE</td>
                <td>{{ $payrollRegister->sss_mpf_ee_15 }}</td>
            </tr>
            <tr>
                <td>Undertime</td>
                <td>{{ $payrollRegister->undertime_amount }}</td>
                <td>PhilHealth Contribution – EE</td>
                <td>{{ $payrollRegister->phic_ee_15 }}</td>
            </tr>
            <tr>
                <td>Salary Adjustment</td>
                <td>{{ $payrollRegister->salary_adjustment }}</td>
                <td>Pag-IBIG Contribution – EE</td>
                <td>{{ $payrollRegister->hmdf_ee_15 }}</td>
            </tr>
            <tr>
                <td>Regular Overtime Pay</td>
                <td>{{ $payrollRegister->overtime_pay }}</td>
                <td>Pag-IBIG Salary Loan</td>
                <td>{{ $payrollRegister->hdmf_salary_loan }}</td>
            </tr>
            <tr>
                <td>Rest Day Pay</td>
                <td>{{ $payrollRegister->hdmf_salary_loan }}</td>
                <td>Pag-IBIG Calamity Loan</td>
                <td>{{ $payrollRegister->hdmf_calamity_loan }}</td>
            </tr>
            <tr>
                <td>SH/RD Overtime Pay</td>
                <td></td>
                <td>SSS Salary Loan</td>
                <td>{{ $payrollRegister->sss_salary_loan }}</td>
            </tr>
            <tr>
                <td>Special Holiday Pay</td>
                <td></td>
                <td>SSS Calamity Loan</td>
                <td>{{ $payrollRegister->sss_calamity_loan }}</td>
            </tr>
            <tr>
                <td>SH on RD Pay</td>
                <td></td>
                <td>Salary Deduction (Taxable)</td>
                <td>{{ $payrollRegister->salary_deduction_taxable }}</td>
            </tr>
            <tr>
                <td>Regular Holiday Pay</td>
                <td></td>
                <td>Salary Deduction (Non Taxable)</td>
                <td>{{ $payrollRegister->salary_deduction_nontaxable }}</td>
            </tr>
            <tr>
                <td>Night Differential Pay</td>
                <td></td>
                <td>Company Loan</td>
                <td>{{ $payrollRegister->company_loan }}</td>
            </tr>
            <tr>
                <td>Overtime Adjustment</td>
                <td></td>
                <td>OMHAS (Advances from MAC)</td>
                <td>{{ $payrollRegister->omhas_loan }}</td>
            </tr>
            <tr>
                <td>Meal Allowance</td>
                <td>{{ $payrollRegister->meal_allowance }}</td>
                <td>COOP CBU</td>
                <td>{{ $payrollRegister->coop_cbu }}</td>
            </tr>
            <tr>
                <td>Salary Allowance</td>
                <td>{{ $payrollRegister->salary_allowance }}</td>
                <td>COOP Regular Loan</td>
                <td>{{ $payrollRegister->coop_regular_loan }}</td>
            </tr>
            <tr>
                <td>Out of Town Allowance</td>
                <td>{{ $payrollRegister->out_of_town_allowance }}</td>
                <td>COOP MESCCO</td>
                <td>{{ $payrollRegister->coop_mescco }}</td>
            </tr>
            <tr>
                <td>Relocation Allowance</td>
                <td>{{ $payrollRegister->relocation_allowance }}</td>
                <td>Uploan</td>
                <td></td>
            </tr>
            <tr>
                <td>Discretionary Allowance</td>
                <td>{{ $payrollRegister->discretionary_allowance }}</td>
                <td>Tax Refund / Payable</td>
                <td></td>
            </tr>

            <!-- More Income Rows -->
            <tr>
                <td>Transpo Allowance</td>
                <td>{{ $payrollRegister->transport_allowance }}</td>
                <td>SSS Regular – Employer Share</td>
                <td>{{ $payrollRegister->sss_reg_er_15 }}</td>
            </tr>
            <tr>
                <td>Load Allowance</td>
                <td>{{ $payrollRegister->load_allowance }}</td>
                <td>SSS MPF – Employer Share</td>
                <td>{{ $payrollRegister->sss_mpf_ee_15 }}</td>
            </tr>
            <tr>
                <td>13th Month Pay</td>
                <td></td>
                <td>SSS – EC</td>
                <td>{{ $payrollRegister->sss_ec_15 }}</td>
            </tr>
            <tr>
                <td>Total Gross Pay</td>
                <td>{{ $payrollRegister->grosspay }}</td>
                <td>PhilHealth – Employer Share</td>
                <td>{{ $payrollRegister->phic_er_15 }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Pag-IBIG – Employer Share</td>
                <td>{{ $payrollRegister->hdmf_er_15 }}</td>
            </tr>
            <tr>
                <td><strong>NET PAY:</strong></td>
                <td>{{ $payrollRegister->netpay }}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
