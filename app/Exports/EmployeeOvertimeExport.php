<?php

namespace App\Exports;

use App\EmployeeOvertime;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeOvertimeExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct($company,$from,$to)
    {
        $this->company = $company;
        $this->from = $from;
        $this->to = $to;
    }

    public function query()
    {
        $company = $this->company;
        return EmployeeOvertime::query()->with('user','employee')
                                ->whereDate('approved_date','>=',$this->from)
                                ->whereDate('approved_date','<=',$this->to)
                                ->whereHas('employee',function($q) use($company){
                                    $q->where('company_id',$company);
                                })
                                ->where('status','Approved');
    }



    public function headings(): array
    {
        return [
            'User ID',
            'Employee Name',
            // 'Date Filed',
            'OT Date',
            // 'OT Start Time',
            // 'OT End Time',
            // 'OT Requested',
            'OT Approved',
            // 'Approved Date',
            // 'Status',
            // 'Remarks',
            'RW OT'
        ];
    }

    public function map($employee_leave): array
    {
        $rw_ot = $this->isRWOT($employee_leave->ot_date);
        return [
            $employee_leave->user->id,
            $employee_leave->user->name,
            // date('d/m/Y', strtotime($employee_leave->created_at)),
            date('d/m/Y',strtotime($employee_leave->ot_date)),
            // date('H:i', strtotime($employee_leave->start_time)),
            // date('H:i', strtotime($employee_leave->end_time)),
            // intval((strtotime($employee_leave->end_time)-strtotime($employee_leave->start_time))/60/60),
            // date('H:i',strtotime($employee_leave->ot_approved_hrs)),
            gmdate('H:i', floor($employee_leave->ot_approved_hrs * 3600)),
            $rw_ot,
            // date('d/m/Y',strtotime($employee_leave->approved_date)),
            // $employee_leave->status,
            // $employee_leave->remarks
        ];
    }


    public function isRWOT( $date ) {

        $check_day = date('D',strtotime($date));
        $check = '1';
        if ($check_day == 'Sat' || $check_day == 'Sun') {
            $check = '0';
        }else{
            $check = '1';
        }
        return $check;
    }

}
