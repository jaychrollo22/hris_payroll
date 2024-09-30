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
        $employee = PayrollEmployeeContribution::query()->with('employee.company');

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
            'USER ID',
            'SSS REG EE',
            'SSS MPF EE',
            'PHIC EE',
            'HDMF EE',
            'SSS REG ER',
            'SSS MPF ER',
            'SSS EC',
            'PHIC ER',
            'HDMF ER',
            'Payment Schedule',
        ];
    }

    public function map($contribution): array
    {
        $employee_name = $employee_allowance->employee ? $employee_allowance->employee->last_name . ', ' . $employee_allowance->employee->first_name . ' ' . $employee_allowance->employee->middle_name : "";

        $company = '';
        if($employee_allowance->employee){
            if($employee_allowance->employee->company){
                $company = $employee_allowance->employee->company->company_name;
            }
        }

        return [
            $employee_number,
            $particular,
            $employee_allowance->description,
            $employee_allowance->application,
            $employee_allowance->type,
            $schedule,
            $employee_allowance->allowance_amount,
            $employee_allowance->end_date
        ];
    }

}
