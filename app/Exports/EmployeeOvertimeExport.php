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

        // <th>Date Filed</th>
        // <th>OT Date</th> 
        // <th>OT Time</th> 
        // <th>OT Requested (Hrs)</th>
        // <th>OT Approved (Hrs)</th>
        // <th>Remarks </th>
        // <th>Approvers </th>
        // <th>Status </th>
        return [
            'User ID',
            'Employee Name',
            'Date Filed',
            'OT Date',
            'OT Time',
            'OT Requested',
            'OT Approved',
            'Remarks',
        ];
    }

    public function map($employee_leave): array
    {
        return [
            $employee_leave->user->id,
            $employee_leave->user->name,
            date('d/m/Y', strtotime($employee_leave->created_at)),
            date('d/m/Y',strtotime($employee_leave->ot_date)),
            date('h:i A', strtotime($employee_leave->start_time)) . '-' . date('h:i A', strtotime($employee_leave->end_time)),
            intval((strtotime($employee_leave->end_time)-strtotime($employee_leave->start_time))/60/60),
            $employee_leave->ot_approved_hrs,
            $employee_leave->remarks
        ];
    }
}
