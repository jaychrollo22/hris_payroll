<?php

namespace App\Exports;

use App\Company;
use App\Employee;
use App\PayrollOvertimeAdjustment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class PayrollOvertimeAdjustmentExport implements FromQuery, WithHeadings, WithMapping
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

        // Fetch overtime adjustments
        $overtime_adjustments = PayrollOvertimeAdjustment::where('status',$status)
            ->whereHas('employee',function($q) use($allowed_companies){
                $q->whereIn('company_id',$allowed_companies);
            })
            ->with('employee.company');

        if($company){
            $overtime_adjustments = $overtime_adjustments->whereHas('employee',function($q) use($company){
                $q->where('company_id',$company);
            });
        }

        if($payroll_period) $overtime_adjustments = $overtime_adjustments->where('payroll_period_id',$payroll_period);

        return $overtime_adjustments;
    }

    public function headings(): array
    {
        return [
            'USER ID',
            'NAME',
            'COMPANY',
            'AMOUNT',
            'TYPE',
            'STATUS',
            'REASON',
            'PAYROLL CUT-OFF'
        ];
    }

    public function map($overtime_adjustment): array
    {
        $employee_number = $overtime_adjustment->employee ? $overtime_adjustment->employee->employee_number : "";
        $employee_name = $overtime_adjustment->employee ? $overtime_adjustment->employee->last_name . ', ' . $overtime_adjustment->employee->first_name . ' ' . $overtime_adjustment->employee->middle_name : "";
        $company = '';
        if($overtime_adjustment->employee){
            if($overtime_adjustment->employee->company) $company = $overtime_adjustment->employee->company->company_name;
        }

        return [
            $employee_number,
            $employee_name,
            $company,
            $overtime_adjustment->amount,
            $overtime_adjustment->type,
            $overtime_adjustment->status,
            $overtime_adjustment->reason,
            $overtime_adjustment->payroll_cutoff,
        ];
    }
}
