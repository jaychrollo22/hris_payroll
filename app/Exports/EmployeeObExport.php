<?php

namespace App\Exports;

use App\EmployeeOb;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeObExport implements FromQuery, WithHeadings, WithMapping
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
        return EmployeeOb::query()->with('user','employee')
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
            'Date Filed',
            'OB From',
            'OB To',
            'OB Count',
            'Approved Date',
            'Status',
            'Remarks',
        ];
    }

    public function map($employee_ob): array
    {
        return [
            $employee_ob->user->id,
            $employee_ob->user->name,
            date('d/m/Y', strtotime($employee_ob->created_at)),
            date('d/m/Y',strtotime($employee_ob->date_from)),
            date('d/m/Y',strtotime($employee_ob->date_to)),
            $this->get_count_days($employee_ob->schedule,$employee_ob->date_from,$employee_ob->date_to),
            date('d/m/Y',strtotime($employee_ob->approved_date)),
            $employee_ob->status,
            $employee_ob->remarks
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
