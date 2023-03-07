<?php

namespace App\Exports;

use App\EmployeeWfh;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeWfhExport implements FromQuery, WithHeadings, WithMapping
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
        return EmployeeWfh::query()->with('user','employee')
                                ->whereDate('date_from','>=',$this->from)
                                ->whereDate('date_from','<=',$this->to)
                                ->whereHas('employee',function($q) use($company){
                                    $q->where('company_id',$company);
                                })
                                ->where('status','Approved');
    }



    public function headings(): array
    {
        return [
            'USER ID',
            'EMPLOYEE NAME',
            'DATE',
            'FIRST ACTUAL TIME IN',
            'SECOND ACTUAL TIME OUT',
            // 'WFH Count',
            // 'Approved Date',
            // 'Status',
            'REMARKS',
        ];
    }

    public function map($employee_wfh): array
    {
        return [
            $employee_wfh->user->id,
            $employee_wfh->user->name,
            // date('d/m/Y', strtotime($employee_wfh->created_at)),
            date('d/m/Y',strtotime($employee_wfh->date_from)),
            date('H:i',strtotime($employee_wfh->date_from)),
            date('H:i',strtotime($employee_wfh->date_to)),
            // $this->get_count_days($employee_wfh->schedule,$employee_wfh->date_from,$employee_wfh->date_to),
            // date('d/m/Y',strtotime($employee_wfh->approved_date)),
            // $employee_wfh->status,
            "WFH"
        ];
    }

    public function get_count_days($data,$date_from,$date_to)
    {
       $data = ($data->pluck('name'))->toArray();
       $count = 0;
       $startTime = strtotime($date_from);
       $endTime = strtotime($date_to);
   
       for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
         $thisDate = date( 'l', $i ); // 2010-05-01, 2010-05-02, etc
         if(in_array($thisDate,$data)){
             $count= $count+1;
         }
       }
   
       return($count);
    } 
}
