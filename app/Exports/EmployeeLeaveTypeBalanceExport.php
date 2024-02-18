<?php

namespace App\Exports;

use App\EmployeeLeaveTypeBalance;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeLeaveTypeBalanceExport implements FromQuery, WithHeadings, WithMapping
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
        

        $employee_leave_type_balances = EmployeeLeaveTypeBalance::with('user','employee.company','employee.department','leave_type_info')
                                                ->whereHas('employee',function($q) use($allowed_companies){
                                                    $q->whereIn('company_id',$allowed_companies);
                                                })
                                                ->where('status',$status);

        if($company){
            $employee_leave_type_balances = $employee_leave_type_balances
                                            ->whereHas('employee',function($q) use($company){
                                                $q->where('company_id',$company);
                                            });
        }
        if($department){
            $employee_leave_type_balances = $employee_leave_type_balances
                                            ->whereHas('employee',function($q) use($department){
                                                $q->where('department_id',$department);
                                            });
        }

        return $employee_leave_type_balances;
    }

    public function headings(): array
    {
        return [
            'User ID',
            'Employee',
            'Company',
            'Department',
            'Date Hired',
            'Year',
            'Leave Type',
            'Leave Balance',
            'Used',
            'Remaining',
        ];
    }

    public function map($leave_balance): array
    {
        $employee = $leave_balance->employee ? $leave_balance->employee->first_name  . ' ' . $leave_balance->employee->last_name : "";
        $company = $leave_balance->employee ? $leave_balance->employee->company->company_name : "";
        $department = $leave_balance->employee ? $leave_balance->employee->department->name : "";
        $date_hired = $leave_balance->employee ? $leave_balance->employee->original_date_hired : "";

        $used_leave = checkUsedLeave($leave_balance->user_id,$leave_balance->leave_type_info->id,$leave_balance->year);
        $total_balance = $leave_balance->balance;
        $remaining = $leave_balance->balance - $used_leave;

        if($remaining > 0){
            $remaining = $remaining;
        }else{
            $remaining = 0;
        }

        if($used_leave > 0){
            $used_leave = $used_leave;
        }else{
            $used_leave = 0;
        }

        return [
            $leave_balance->user_id,
            $employee,
            $company,
            $department,
            $date_hired,
            $leave_balance->year,
            $leave_balance->leave_type,
            $leave_balance->balance,
            $used_leave,
            $remaining
        ];
        
    }


}
