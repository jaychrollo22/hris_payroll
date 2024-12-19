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

        $payroll_registers = PayrollAttendance::with('timeKeeper','overtimeApprover')
            ->whereHas('employee',function($q) use($allowed_companies){
                $q->whereIn('company_id',$allowed_companies);
            })
            ->with('employee.company');
        if($company){
            $payroll_registers = $payroll_registers->whereHas('employee',function($q) use($company){
                $q->where('company_id',$company);
            });
        }

        if($payroll_period) $payroll_registers = $payroll_registers->where('payroll_period_id',$payroll_period);

        return $payroll_registers;
    }

    /**
     * Define the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID','USER_ID','PAYROLL_PERIOD_ID','FULL_NAME','COMPANY','DEPARTMENT', 'LOCATION', 'BASIC_PAY', 'DAILY_RATE', 'HOURLY_RATE',
            'DAYS_WORKED', 'DAYS_WORK_AMOUNT', 'SICK_LEAVE_DAYS', 'SICK_LEAVE_AMOUNT', 'VACATION_LEAVE_DAYS','VACATION_LEAVE_AMOUNT',
            'ABSENCES_DAYS', 'ABSENCES_AMOUNT','LATE_HOURS', 'LATES_AMOUNT', 'UNDERTIME_HOURS', 'UNDERTIME_AMOUNT', 
            'REGULAR_OT_HOURS', 'REGULAR_OT_AMOUNT', 'REST_DAY_HOURS','REST_DAY_HOURS_AMOUNT','RDOD_SHOT_HOURS','RDOT_SHOT_AMOUNT',
            'SPECIAL_HOLIDAY_HOURS','SPECIAL_HOLIDAY_AMOUNT','SHRD_HOURS','SHRD_AMOUNT','SH_AND_RD_OT_HOURS','SH_AND_RD_OT_AMOUNT',
            'REGULAR_HOLIDAY_HOURS','REGULAR_HOLIDAY_AMOUNT','SH_AND_RD_OR_RH_OT_HOURS','SH_AND_RD_OR_RH_OT_AMOUNT',
            'LHRD_OT_HOURS','LHRD_OT_AMOUNT','NIGHT_DIFF_HOURS','NIGHT_DIFF_AMOUNT','OVERTIME_ADJUSTMENT','TOTAL_OVERTIME_PAY',
            'TIME_KEEPER_ID','TIME_KEEPER','OT_APPROVER_ID','OT_APPROVER','STATUS','REMARKS'
        ];
    }

    public function map($payroll_register): array
    {   

        $timeKeeperId = '';
        $timeKeeper = '';
        $overtimeApproverId = ''; 
        $overtimeApprover = '';

        if($payroll_register->timeKeeper){
            $timeKeeperId = $payroll_register->timeKeeper->id;
            $timeKeeper = ($payroll_register->timeKeeper->first_name . ' ' . $payroll_register->timeKeeper->last_name);
        }

        if($payroll_register->overtimeApprover){
            $overtimeApproverId = $payroll_register->overtimeApprover->id; 
            $overtimeApprover = ($payroll_register->overtimeApprover->first_name . ' ' . $payroll_register->overtimeApprover->last_name);
        }

        return [
            '',
            $payroll_register->user_id,
            $payroll_register->payroll_period_id,
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
            $payroll_register->rest_day_hours,
            $payroll_register->rest_day_amount,
            $payroll_register->rdot_shot_hours,
            $payroll_register->rdot_shot_amount,
            $payroll_register->special_holiday_hours,
            $payroll_register->special_holiday_amount,
            $payroll_register->shrd_hours,
            $payroll_register->shrd_amount,
            $payroll_register->sh_rd_ot_hours,
            $payroll_register->sh_rd_ot_amount,
            $payroll_register->regular_holiday_hours,
            $payroll_register->regular_holiday_amount,
            $payroll_register->rh_rd_or_lh_ot_hours,
            $payroll_register->rh_rd_or_lh_ot_amount,
            $payroll_register->lhrd_ot_hours,
            $payroll_register->lhrd_ot_amount,
            $payroll_register->night_diff_hours,
            $payroll_register->night_diff_amount,
            $payroll_register->overtime_adjustment,
            $payroll_register->total_overtime_pay,
            $timeKeeperId,
            $timeKeeper,
            $overtimeApproverId,
            $overtimeApprover,
            $payroll_register->status,
            $payroll_register->remarks
        ];
    }
}
