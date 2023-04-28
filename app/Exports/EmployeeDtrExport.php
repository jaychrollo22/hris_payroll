<?php

namespace App\Exports;

use App\EmployeeDtr;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeDtrExport implements FromQuery, WithHeadings, WithMapping
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
        return EmployeeDtr::query()->with('user','employee')
                                ->whereDate('dtr_date','>=',$this->from)
                                ->whereDate('dtr_date','<=',$this->to)
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
            'Date Filed',
            'DTR Date',
            'Correction',
            'Time In',
            'Time Out',
            'Approved Date',
            'Status',
            'Remarks',
        ];
    }

    public function map($employee_dtr): array
    {
        return [
            $employee_dtr->user->id,
            $employee_dtr->user->name,
            date('d/m/Y', strtotime($employee_dtr->created_at)),
            date('d/m/Y',strtotime($employee_dtr->dtr_date)),
            $employee_dtr->correction,
            (isset($employee_dtr->time_in)) ? date('H:i', strtotime($employee_dtr->time_in)) : '-',
            (isset($employee_dtr->time_out)) ? date('H:i', strtotime($employee_dtr->time_out)) : '-',
            date('d/m/Y', strtotime($employee_dtr->approved_date)),
            $employee_dtr->status,
            $employee_dtr->remarks
        ];
    }

}
