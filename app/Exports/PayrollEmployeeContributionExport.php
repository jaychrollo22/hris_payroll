<?php

namespace App\Exports;

use App\PayrollEmployeeContribution;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayrollEmployeeContributionExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct($company)
    {
        $this->company = $company;
    }

    public function query()
    {
        $company = $this->company;
        $employee = PayrollEmployeeContribution::query()->with('employee.company','payrollPeriod');

        if($company){
            $employee = $employee->whereHas('employee',function($q) use($company){
                $q->where('company_id',$company);
            });
        }
        return $employee;
    }

    public function headings(): array
    {
        return [
            'EMPLOYEE',
            'PAYROLL PERIOD',
            'COMPANY',
            'SSS REG EE',
            'SSS MPF EE',
            'PHIC EE',
            'HDMF EE',
            'SSS REG ER',
            'SSS MPF ER',
            'SSS EC',
            'PHIC ER',
            'HDMF ER',
            'PAYMENT SCHEDULE',
        ];
    }

    public function map($employee_allowance): array
    {
        $employee_name = $employee_allowance->employee ? $employee_allowance->employee->last_name . ', ' . $employee_allowance->employee->first_name . ' ' . $employee_allowance->employee->middle_name : "";
        $payroll_period = $employee_allowance->payrollPeriod ? $employee_allowance->payrollPeriod->payroll_name  : "";

        $company = '';
        if($employee_allowance->employee){
            if($employee_allowance->employee->company){
                $company = $employee_allowance->employee->company->company_name;
            }
        }

        return [
            $employee_name,
            $payroll_period,
            $company,
            $employee_allowance->sss_reg_ee,
            $employee_allowance->sss_mpf_ee,
            $employee_allowance->phic_ee,
            $employee_allowance->hdmf_ee,
            $employee_allowance->sss_reg_er,
            $employee_allowance->sss_mpf_er,
            $employee_allowance->sss_ec,
            $employee_allowance->phic_er,
            $employee_allowance->hdmf_er,
            $employee_allowance->payment_schedule
        ];
    }

}
