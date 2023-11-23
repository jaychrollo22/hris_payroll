<?php

namespace App\Exports;

use App\EmployeeAllowance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class EmployeeAllowanceExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct($company,$status)
    {
        $this->company = $company;
        $this->status = $status;
    }

    public function query()
    {
        $company = $this->company;
        $status = $this->status;
        return EmployeeAllowance::query()->with('employee')
                            ->whereHas('employee',function($q) use($company){
                                $q->where('company_id',$company);
                            })
                            ->where('status',$status);
    }

    public function headings(): array
    {
        return [
            'USER ID',
            'PARTICULAR',
            'DESCRIPTION',
            'APPLICATION',
            'TYPE',
            'CREDIT SCHEDULE',
            'AMOUNT',
            'END DATE',
        ];
    }

    public function map($employee_allowance): array
    {
        $employee_number = $employee_allowance->employee ? $employee_allowance->employee->employee_number : "";
        $particular = $employee_allowance->allowance ? $employee_allowance->allowance->name : "";
        return [
            $employee_number,
            $particular,
            $employee_allowance->description,
            $employee_allowance->application,
            $employee_allowance->type,
            $employee_allowance->schedule,
            $employee_allowance->allowance_amount,
            $employee_allowance->end_date
        ];
    }





}
