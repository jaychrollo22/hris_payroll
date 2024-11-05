<?php

namespace App\Exports;

use App\EmployeeDeduction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeDeductionExport implements FromQuery, WithHeadings, WithMapping
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
        $employee = EmployeeDeduction::query()->with('employee.company','deduction')->where('status',$status);

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
            'NAME',
            'DEDUCTION ID',
            'DEDUCTION',
            'AMOUNT',
            'NO. OF YEARS DEDUCTION',
            'AMORTIZATION',
            'TYPE OF DEDUCTION',
        ];
    }

    public function map($employee_deduction): array
    {

        $user_id = $employee_deduction->employee ? $employee_deduction->employee->user_id : "";
        $name = $employee_deduction->employee ? $employee_deduction->employee->first_name . ' ' . $employee_deduction->employee->last_name  : "";
        
        $company = '';
        if($employee_deduction->employee){
            if($employee_deduction->employee->company){
                $company = $employee_deduction->employee->company->company_name;
            }
        }

        $deduction_name=  $employee_deduction->deduction ? $employee_deduction->deduction->name : "";

        return [
            $employee_number,
            $name,
            $employee_deduction->deduction_id,
            $deduction_name,
            $employee_deduction->no_of_years_deduction,
            $employee_deduction->amortization,
            $employee_deduction->type_of_deduction
        ];

    }



}
