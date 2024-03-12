<?php

namespace App\Exports;

use App\EmployeeLoan;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class EmployeeLoanExport implements FromQuery, WithHeadings, WithMapping
{

    public function __construct($company,$department,$allowed_companies,$status)
    {
        $this->company = $company;
        $this->department = $department;
        $this->allowed_companies = $allowed_companies;
        $this->status = $status;
    }

    public function query()
    {
        $company = $this->company;
        $department = $this->department;
        $allowed_companies = json_decode($this->allowed_companies);
        $status = $this->status;
        

        $employee_loans = EmployeeLoan::with('user','employee.company','employee.department')
                                                ->whereHas('employee',function($q) use($allowed_companies){
                                                    $q->whereIn('company_id',$allowed_companies);
                                                })
                                                ->where('status',$status);

        if($company){
            $employee_loans = $employee_loans->whereHas('employee',function($q) use($company){
                                                $q->where('company_id',$company);
                                            });
        }
        if($department){
            $employee_loans = $employee_loans->whereHas('employee',function($q) use($department){
                                                $q->where('department_id',$department);
                                            });
        }

        return $employee_loans;
    }

    public function headings(): array
    {
        return [
            'User ID',
            'Employee',
            'Company',
            'Department',
            'Date Hired',
            'Collection Date',
            'Due Date',
            'Particular',
            'Description',
            'Credit Schedule',
            'Credit Company',
            'Credit Branch ',
            'Payable Amount',
            'Payable Adjustment',
            'Outright Deduction Bolean',
            'Monthly Deduction'
        ];
    }

    public function map($loan): array
    {
        $employee_number = $loan->employee ? $loan->employee->employee_number : "";
        $employee = $loan->employee ? $loan->employee->first_name  . ' ' . $loan->employee->last_name : "";
        $company = $loan->employee ? $loan->employee->company->company_name : "";
        $department = $loan->employee ? $loan->employee->department->name : "";
        $date_hired = $loan->employee ? $loan->employee->original_date_hired : "";
        $outright_deduction_bolean = $loan->outright_deduction_bolean == 1 ? '1' : '0';
        return [
            $employee_number,
            $employee,
            $company,
            $department,
            $date_hired,
            $loan->collection_date,
            $loan->due_date,
            $loan->particular,
            $loan->description,
            $loan->credit_schedule,
            $loan->credit_company,
            $loan->credit_branch,
            $loan->payable_amount,
            $loan->payable_adjustment,
            $outright_deduction_bolean,
            $loan->monthly_deduction
        ];
        
    }
}
