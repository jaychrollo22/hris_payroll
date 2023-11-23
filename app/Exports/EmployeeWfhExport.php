<?php

namespace App\Exports;

use App\EmployeeWfh;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeWfhExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct($company,$from,$to,$percentage)
    {
        $this->company = $company;
        $this->from = $from;
        $this->to = $to;
        $this->percentage = $percentage;
    }

    public function query()
    {
        $company = $this->company;
        $percentage = $this->percentage;
        return EmployeeWfh::query()->with('user','employee')
                                ->whereDate('applied_date','>=',$this->from)
                                ->whereDate('applied_date','<=',$this->to)
                                ->when(!empty($percentage),function($q) use($percentage){
                                    $q->where('approve_percentage','=',$percentage);
                                })
                                ->whereHas('employee',function($q) use($company){
                                    $q->where('company_id',$company);
                                })
                                ->where('status','Approved');
       
    }



    public function headings(): array
    {
        return [
            'USER ID',
            'DATE',
            'FIRST ACTUAL TIME IN',
            'SECOND ACTUAL TIME OUT',
            'REMARKS',
        ];
    }

    public function map($employee_wfh): array
    {
        $remarks = 'WFH-' . $employee_wfh->approve_percentage . '%';
        return [
            $employee_wfh->employee->employee_number,
            date('d/m/Y',strtotime($employee_wfh->applied_date)),
            date('H:i',strtotime($employee_wfh->date_from)),
            date('H:i',strtotime($employee_wfh->date_to)),
            $remarks
        ];
    }

    public function get_count_days($data,$date_from,$date_to)
    {
       $data = ($data->pluck('name'))->toArray();
       $count = 0;
       $startTime = strtotime($date_from);
       $endTime = strtotime($date_to);
   
       for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
         $thisDate = date( 'l', $i );
         if(in_array($thisDate,$data)){
             $count= $count+1;
         }
       }
   
       return($count);
    } 
}
