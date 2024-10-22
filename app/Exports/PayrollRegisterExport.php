<?php

namespace App\Exports;

use App\Company;
use App\Employee;
use App\PayrollRegister;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayrollRegisterExport implements FromQuery, WithHeadings, WithMapping
{

    public function __construct($company,$department,$payroll_period)
    {
        $this->company = $company;
        $this->department = $department;
        $this->payroll_period = $payroll_period;
    }

    public function query()
    {
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        $company = $this->company ? $this->company : "";
        $department = $this->department ? $this->department : "";
        $payroll_period = $this->payroll_period ? $this->payroll_period : "";

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $payroll_registers = PayrollRegister::where('payroll_period_id',$payroll_period);
        
        if($department){
            $payroll_registers->whereHas('employee',function($q) use($department){
                $q->where('department_id',$department);
            });
        }

        if($company){
            $payroll_registers->whereHas('employee',function($q) use($company){
                $q->where('company_id',$company);
            });

        }

        return $payroll_registers;
    }

    /**
     * Define the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID', 'USER ID', 'BANK ACCOUNT', 'NAME', 'POSITION', 'EMPLOYMENT STATUS', 'COMPANY', 'DEPARTMENT', 
            'PROJECT', 'DATE HIRED', 'CUT FROM', 'CUT TO', 'MONTHLY BASIC PAY', 'DAILY RATE', 'BASIC PAY',
            'ABSENCES AMOUNT', 'LATES AMOUNT', 'UNDERTIME AMOUNT', 'SALARY ADJUSTMENT', 'OVERTIME PAY', 
            'MEAL ALLOWANCE', 'SALARY ALLOWANCE', 'OUT OF TOWN ALLOWANCE', 'INCENTIVES ALLOWANCE', 
            'RELOCATION ALLOWANCE', 'DISCRETIONARY ALLOWANCE', 'TRANSPORT ALLOWANCE', 'LOAD ALLOWANCE', 
            'GROSSPAY', 'TOTAL TAXABLE', 'MINIMUM WAGE', 'WITHOLDING TAX', 'SSS REG EE', 'SSS MPF EE', 
            'PHIC EE', 'HMDF EE', 'HDMF SALARY LOAN', 'HDMF CALAMITY LOAN', 'SSS SALARY LOAN', 
            'SSS CALAMITY LOAN', 'SALARY DEDUCTION TAXABLE', 'SALARY DEDUCTION NON-TAXABLE', 'COMPANY LOAN', 
            'OMHAS', 'COOP CBU', 'COOP REGULAR LOAN', 'COOP MESCCO', 'PETTY CASH MESCCO', 'OTHERS', 
            'TOTAL DEDUCTION', 'NETPAY', 'SSS REG ER', 'SSS MPF ER', 'SSS EC', 'PHIC ER', 'HDMF ER', 
            'BANK', 'STATUS', 'REMARKS', 'STATUS LAST PAYROLL', 'SSS NO.', 'PHILHEALTH NO.', 'PAG-IBIG NO.', 
            'TIN NO.', 'BIR TAGGING', 'SEPT 15', 'SEPT 30', 'ACCUMULATED', 'NUMBER', 'CREATED AT', 'UPDATED AT'
        ];
    }

    public function map($payroll_register): array
    {
        return [
            $payroll_register->id,
            $payroll_register->user_id,
            $payroll_register->bank_account,
            $payroll_register->name,
            $payroll_register->position,
            $payroll_register->employment_status,
            $payroll_register->company,
            $payroll_register->department,
            $payroll_register->project,
            $payroll_register->date_hired,
            $payroll_register->payroll_period_id,
            $payroll_register->payroll_period_id,
            $payroll_register->monthly_basic_pay,
            $payroll_register->daily_rate,
            $payroll_register->basic_pay,
            $payroll_register->absences_amount,
            $payroll_register->lates_amount,
            $payroll_register->undertime_amount,
            $payroll_register->salary_adjustment,
            $payroll_register->overtime_pay,
            $payroll_register->meal_allowance,
            $payroll_register->salary_allowance,
            $payroll_register->out_of_town_allowance,
            $payroll_register->incentives_allowance,
            $payroll_register->relocation_allowance,
            $payroll_register->discretionary_allowance,
            $payroll_register->transport_allowance,
            $payroll_register->load_allowance,
            $payroll_register->grosspay,
            $payroll_register->total_taxable,
            $payroll_register->minimum_wage,
            $payroll_register->withholding_tax,
            $payroll_register->sss_reg_ee_15,
            $payroll_register->sss_mpf_ee_15,
            $payroll_register->phic_ee_15,
            $payroll_register->hmdf_ee_15,
            $payroll_register->hdmf_salary_loan,
            $payroll_register->hdmf_calamity_loan,
            $payroll_register->sss_salary_loan,
            $payroll_register->sss_calamity_loan,
            $payroll_register->salary_deduction_taxable,
            $payroll_register->salary_deduction_nontaxable,
            $payroll_register->company_loan,
            $payroll_register->omhas_loan,
            $payroll_register->coop_cbu,
            $payroll_register->coop_regular_loan,
            $payroll_register->coop_mescco,
            $payroll_register->petty_cash_mescco,
            $payroll_register->others,
            $payroll_register->total_deduction,
            $payroll_register->netpay,
            $payroll_register->sss_reg_er_15,
            $payroll_register->sss_mpf_er_15,
            $payroll_register->sss_ec_15,
            $payroll_register->phic_er_15,
            $payroll_register->hdmf_er_15,
            $payroll_register->bank,
            $payroll_register->status,
            $payroll_register->remarks,
            $payroll_register->status_last_payroll,
            $payroll_register->sss_no,
            $payroll_register->philhealth_no,
            $payroll_register->pagibig_no,
            $payroll_register->tin_no,
            $payroll_register->bir_tagging,
            $payroll_register->month_15,
            $payroll_register->month_30,
            $payroll_register->accumulated,
            $payroll_register->number,
            $payroll_register->created_at,
            $payroll_register->updated_at
        ];
    }
}
