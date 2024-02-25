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
        $employee = EmployeeAllowance::query()->with('employee.schedule_info','employee.company')->where('status',$status);

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
            // 'EMPLOYEE ID NUMBER', // NEW
            // 'EMPLOYEE NAME', // NEW
            'PARTICULAR',
            'DESCRIPTION',
            'APPLICATION',
            'TYPE',
            'CREDIT SCHEDULE',
            // 'CREDIT COMPANY', // New
            // 'CREDIT BRANCH', // New
            'AMOUNT',
            'END DATE',
        ];
    }

    public function map($employee_allowance): array
    {
        $employee_number = $employee_allowance->employee ? $employee_allowance->employee->employee_number : "";
        $user_id = $employee_allowance->employee ? $employee_allowance->employee->user_id : "";
        $particular = $employee_allowance->allowance ? $employee_allowance->allowance->name : "";

        $schedule = $employee_allowance->schedule;

        if($schedule == 'Bi-monthly' || $schedule == 'bi-monthly'){
            $schedule = 'Every Cut-Off';
        }

        $employee_name = $employee_allowance->employee ? $employee_allowance->employee->last_name . ', ' . $employee_allowance->employee->first_name . ' ' . $employee_allowance->employee->middle_name : "";

        $branch = '';
        if($employee_allowance->employee->schedule_info){
            if($employee_allowance->employee->schedule_info->is_flexi == 1){
                $branch = 'BRANCH 2';
            }
            else if($employee_allowance->employee->schedule_info->id == 9){
                $branch = 'BRANCH 3';
            }else{
                $branch = 'BRANCH 1';
            }   
        }

        $company = '';
        if($employee_allowance->employee->company){
            $company = $employee_allowance->employee->company->company_name;
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
