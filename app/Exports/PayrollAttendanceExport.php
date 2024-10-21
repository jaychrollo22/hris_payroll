<?php

namespace App\Exports;

use App\Company;
use App\PayrollAttendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayrollAttendanceExport implements FromQuery, WithHeadings, WithMapping
{

    public function __construct($company,$payroll_period)
    {
        $this->company = $company;
        $this->payroll_period = $payroll_period;
    }

    public function query()
    {
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $company = isset($this->company) ?  $this->company : "";
        $payroll_period = isset($this->payroll_period) ? $this->payroll_period : "";

        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        $payroll_attendances = PayrollAttendance::whereHas('employee',function($q) use($allowed_companies){
                                                            $q->whereIn('company_id',$allowed_companies);
                                                        })
                                                        ->with('employee.company');
        if($company){
            $payroll_attendances = $payroll_attendances->whereHas('employee',function($q) use($company){
                $q->where('company_id',$company);
            });
        }

        if($payroll_period) $payroll_attendances = $payroll_attendances->where('payroll_period_id',$payroll_period);

        return $payroll_attendances;
    }

    /**
     * Define the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'USER ID', 'NAME','COMPANY','DEPARTMENT', 'LOCATION', 'BASIC PAY', 'DAILY RATE', 'HOURLY RATE',
            'DAYS WORKED', 'DAYS WORK AMOUNT', 'SICK LEAVE DAYS', 'SICK LEAVE AMOUNT', 'VACATION LEAVE DAYS','VACATION LEAVE AMOUNT',
            'ABSENCES DAYS', 'ABSENCES AMOUNT','LATE HOURS', 'LATES AMOUNT', 'UNDERTIME HOURS', 'UNDERTIME AMOUNT', 
            'REGULAR OT HOURS', 'REGULAR OT AMOUNT', 'OVERTIME ADJUSTMENT', 'TOTAL OVERTIME PAY', 'STATUS','REMARKS'
        ];
    }

    public function map($payroll_register): array
    {
        return [
            $payroll_register->user_id,
            $payroll_register->full_name,
            $payroll_register->company,
            $payroll_register->department,
            $payroll_register->location,
            $payroll_register->basic_pay,
            $payroll_register->daily_rate,
            $payroll_register->hourly_rate,
            $payroll_register->no_of_days_worked,
            $payroll_register->days_worked_amount,
            $payroll_register->sl_with_pay_days,
            $payroll_register->sl_with_pay_amount,
            $payroll_register->vl_with_pay_days,
            $payroll_register->vl_with_pay_amount,
            $payroll_register->absences_days,
            $payroll_register->absences_amount,
            $payroll_register->lates_hours,
            $payroll_register->lates_amount,
            $payroll_register->undertime_hours,
            $payroll_register->undertime_amount,
            $payroll_register->reg_ot_hours,
            $payroll_register->reg_ot_amount,
            $payroll_register->overtime_adjustment,
            $payroll_register->total_overtime_pay,
            $payroll_register->status,
            $payroll_register->remarks
        ];
    }
}
