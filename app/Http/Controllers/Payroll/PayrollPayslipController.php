<?php

namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use PDF;

class PayrollPayslipController extends Controller
{
    public function generatePayslip()
    {
        // Dummy data, replace with actual database data as needed
        $data = [
            'name' => 'John Doe',
            'payroll_period' => 'October 2024',
            'employment_status' => 'Full-time',
            'cut_off' => 'October 1 - October 15, 2024',
            'daily_rate' => '1000.00',
            'location' => 'Office',
            'bank' => 'Bank Name',
            'basic_pay' => '15000.00',
            'absences' => '0.00',
            'late' => '0.00',
            'undertime' => '0.00',
            'salary_adjustment' => '0.00',
            'regular_overtime' => '0.00',
            'rest_day_pay' => '0.00',
            'sh_rd_overtime_pay' => '0.00',
            'special_holiday_pay' => '0.00',
            'sh_on_rd_pay' => '0.00',
            'regular_holiday_pay' => '0.00',
            'night_differential_pay' => '0.00',
            'overtime_adjustment' => '0.00',
            'meal_allowance' => '0.00',
            'salary_allowance' => '0.00',
            'out_of_town_allowance' => '0.00',
            'relocation_allowance' => '0.00',
            'discretionary_allowance' => '0.00',
            'transpo_allowance' => '0.00',
            'load_allowance' => '0.00',
            'thirteenth_month_pay' => '0.00',
            'withholding_tax' => '0.00',
            'sss_regular_ee' => '0.00',
            'sss_mpf_ee' => '0.00',
            'philhealth_ee' => '0.00',
            'pagibig_ee' => '0.00',
            'pagibig_salary_loan' => '0.00',
            'pagibig_calamity_loan' => '0.00',
            'sss_salary_loan' => '0.00',
            'sss_calamity_loan' => '0.00',
            'salary_deduction_taxable' => '0.00',
            'salary_deduction_non_taxable' => '0.00',
            'company_loan' => '0.00',
            'omhas' => '0.00',
            'coop_cbu' => '0.00',
            'coop_regular_loan' => '0.00',
            'coop_mescco' => '0.00',
            'uploan' => '0.00',
            'tax_refund' => '0.00',
            'sss_regular_er' => '0.00',
            'sss_mpf_er' => '0.00',
            'sss_ec' => '0.00',
            'philhealth_er' => '0.00',
            'pagibig_er' => '0.00',
            'total_gross_pay' => '15000.00',
            'total_deductions' => '2000.00',
            'net_pay' => '13000.00',
        ];

        $pdf = PDF::loadView('payroll_payslip.print_payslip', $data);
        return $pdf->stream('payroll_payslip.print_payslip');
        // return $pdf->download('payroll_payslip.print_payslip');
    }
}
