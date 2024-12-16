<?php

namespace App\Exports;

use App\Company;
use App\Employee;
use App\PayrollSalaryAdjustment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class PayrollSalaryAdjustmentExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct($company,$status,$payroll_period)
    {
        $this->company = $company;
        $this->status = $status;
        $this->payroll_period = $payroll_period;
    }

    public function query()
    {
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $companies = Company::whereHas('employee_has_company')
            ->whereIn('id',$allowed_companies)
            ->get();

        $company = $this->company ? $this->company : "";
        $status = $this->status ? $this->status : "Active";
        $payroll_period = $this->payroll_period ? $this->payroll_period : "";

        $employees = Employee::select('id','user_id','first_name','last_name','middle_name')
            ->whereIn('company_id',$allowed_companies)
            ->where('status','Active')
            ->get();

        // Fetch salary adjustments
        $salary_adjustments = PayrollSalaryAdjustment::where('status',$status)
            ->whereHas('employee',function($q) use($allowed_companies){
                $q->whereIn('company_id',$allowed_companies);
            })
            ->with('employee.company');

        if($company){
            $salary_adjustments = $salary_adjustments->whereHas('employee',function($q) use($company){
                $q->where('company_id',$company);
            });
        }

        if($payroll_period) $salary_adjustments = $salary_adjustments->where('payroll_period_id',$payroll_period);

        return $salary_adjustments;
    }

    public function headings(): array
    {
        return [
            'USER ID',
            'NAME',
            'COMPANY',
            // 'EFFECTIVITY DATE',
            'AMOUNT',
            'TYPE',
            'STATUS',
            'REASON',
            'PAYROLL CUT-OFF'
        ];
    }

    public function map($salary_adjustment): array
    {
        $employee_number = $salary_adjustment->employee ? $salary_adjustment->employee->employee_number : "";
        $employee_name = $salary_adjustment->employee ? $salary_adjustment->employee->last_name . ', ' . $salary_adjustment->employee->first_name . ' ' . $salary_adjustment->employee->middle_name : "";
        $company = '';
        if($salary_adjustment->employee){
            if($salary_adjustment->employee->company) $company = $salary_adjustment->employee->company->company_name;
        }

        return [
            $employee_number,
            $employee_name,
            $company,
            // $salary_adjustment->effectivity_date,
            $salary_adjustment->amount,
            $salary_adjustment->type,
            $salary_adjustment->status,
            $salary_adjustment->reason,
            $salary_adjustment->payroll_cutoff,
        ];
    }
}
