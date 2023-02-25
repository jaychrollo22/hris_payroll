<?php

namespace App\Exports;

use App\EmployeeLeave;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;



class EmployeeLeaveExport implements FromQuery, WithHeadings, WithMapping
{

    public function __construct($from,$to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function query()
    {
        return EmployeeLeave::query()->with('user','leave')
                                ->whereDate('approved_date','>=',$this->from)
                                ->whereDate('approved_date','<=',$this->to)
                                ->where('status','Approved');
    }



    public function headings(): array
    {
        return [
            'Employee Name',
            'Form Type',
            'From',
            'To',
            'Status',
            'Approved Date',
            'Reason/Remarks',
        ];
    }

    public function map($employee_leave): array
    {
        return [
            $employee_leave->user->name,
            $employee_leave->leave->leave_type,
            date('d/m/Y',strtotime($employee_leave->date_to)),
            date('d/m/Y',strtotime($employee_leave->date_from)),
            $employee_leave->status,
            date('d/m/Y',strtotime($employee_leave->approved_date)),
            $employee_leave->reason
        ];
    }

}
