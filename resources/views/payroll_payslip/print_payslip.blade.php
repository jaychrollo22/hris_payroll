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
            <td>{{ $name }}</td>
            <td><strong>Payroll Period:</strong></td>
            <td>{{ $payroll_period }}</td>
        </tr>
        <tr>
            <td><strong>Employment Status:</strong></td>
            <td>{{ $employment_status }}</td>
            <td><strong>Cut off Covered:</strong></td>
            <td>{{ $cut_off }}</td>
        </tr>
        <tr>
            <td><strong>Daily Rate:</strong></td>
            <td>{{ $daily_rate }}</td>
            <td><strong>Location:</strong></td>
            <td>{{ $location }}</td>
        </tr>
        <tr>
            <td><strong>Bank:</strong></td>
            <td colspan="3">{{ $bank }}</td>
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
                <td>{{ $basic_pay }}</td>
                <td>Withholding Tax</td>
                <td>{{ $withholding_tax }}</td>
            </tr>
            <tr>
                <td>Absences</td>
                <td>{{ $absences }}</td>
                <td>SSS Regular Contribution – EE</td>
                <td>{{ $sss_regular_ee }}</td>
            </tr>
            <tr>
                <td>Late</td>
                <td>{{ $late }}</td>
                <td>SSS MPF Contribution – EE</td>
                <td>{{ $sss_mpf_ee }}</td>
            </tr>
            <tr>
                <td>Undertime</td>
                <td>{{ $undertime }}</td>
                <td>PhilHealth Contribution – EE</td>
                <td>{{ $philhealth_ee }}</td>
            </tr>
            <tr>
                <td>Salary Adjustment</td>
                <td>{{ $salary_adjustment }}</td>
                <td>Pag-IBIG Contribution – EE</td>
                <td>{{ $pagibig_ee }}</td>
            </tr>
            <tr>
                <td>Regular Overtime Pay</td>
                <td>{{ $regular_overtime }}</td>
                <td>Pag-IBIG Salary Loan</td>
                <td>{{ $pagibig_salary_loan }}</td>
            </tr>
            <tr>
                <td>Rest Day Pay</td>
                <td>{{ $rest_day_pay }}</td>
                <td>Pag-IBIG Calamity Loan</td>
                <td>{{ $pagibig_calamity_loan }}</td>
            </tr>
            <tr>
                <td>SH/RD Overtime Pay</td>
                <td>{{ $sh_rd_overtime_pay }}</td>
                <td>SSS Salary Loan</td>
                <td>{{ $sss_salary_loan }}</td>
            </tr>
            <tr>
                <td>Special Holiday Pay</td>
                <td>{{ $special_holiday_pay }}</td>
                <td>SSS Calamity Loan</td>
                <td>{{ $sss_calamity_loan }}</td>
            </tr>
            <tr>
                <td>SH on RD Pay</td>
                <td>{{ $sh_on_rd_pay }}</td>
                <td>Salary Deduction (Taxable)</td>
                <td>{{ $salary_deduction_taxable }}</td>
            </tr>
            <tr>
                <td>Regular Holiday Pay</td>
                <td>{{ $regular_holiday_pay }}</td>
                <td>Salary Deduction (Non Taxable)</td>
                <td>{{ $salary_deduction_non_taxable }}</td>
            </tr>
            <tr>
                <td>Night Differential Pay</td>
                <td>{{ $night_differential_pay }}</td>
                <td>Company Loan</td>
                <td>{{ $company_loan }}</td>
            </tr>
            <tr>
                <td>Overtime Adjustment</td>
                <td>{{ $overtime_adjustment }}</td>
                <td>OMHAS (Advances from MAC)</td>
                <td>{{ $omhas }}</td>
            </tr>
            <tr>
                <td>Meal Allowance</td>
                <td>{{ $meal_allowance }}</td>
                <td>COOP CBU</td>
                <td>{{ $coop_cbu }}</td>
            </tr>
            <tr>
                <td>Salary Allowance</td>
                <td>{{ $salary_allowance }}</td>
                <td>COOP Regular Loan</td>
                <td>{{ $coop_regular_loan }}</td>
            </tr>
            <tr>
                <td>Out of Town Allowance</td>
                <td>{{ $out_of_town_allowance }}</td>
                <td>COOP MESCCO</td>
                <td>{{ $coop_mescco }}</td>
            </tr>
            <tr>
                <td>Relocation Allowance</td>
                <td>{{ $relocation_allowance }}</td>
                <td>Uploan</td>
                <td>{{ $uploan }}</td>
            </tr>
            <tr>
                <td>Discretionary Allowance</td>
                <td>{{ $discretionary_allowance }}</td>
                <td>Tax Refund / Payable</td>
                <td>{{ $tax_refund }}</td>
            </tr>

            <!-- More Income Rows -->
            <tr>
                <td>Transpo Allowance</td>
                <td>{{ $transpo_allowance }}</td>
                <td>SSS Regular – Employer Share</td>
                <td>{{ $sss_regular_er }}</td>
            </tr>
            <tr>
                <td>Load Allowance</td>
                <td>{{ $load_allowance }}</td>
                <td>SSS MPF – Employer Share</td>
                <td>{{ $sss_mpf_er }}</td>
            </tr>
            <tr>
                <td>13th Month Pay</td>
                <td>{{ $thirteenth_month_pay }}</td>
                <td>SSS – EC</td>
                <td>{{ $sss_ec }}</td>
            </tr>
            <tr>
                <td>Total Gross Pay</td>
                <td>{{ $total_gross_pay }}</td>
                <td>PhilHealth – Employer Share</td>
                <td>{{ $philhealth_er }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Pag-IBIG – Employer Share</td>
                <td>{{ $pagibig_er }}</td>
            </tr>
            <tr>
                <td><strong>NET PAY:</strong></td>
                <td>{{ $net_pay }}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
