<?php

namespace App\Exports;

use App\Holiday;
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
                                ->whereDate('ot_date','>=',$this->from)
                                ->whereDate('ot_date','<=',$this->to)
                                ->whereHas('employee',function($q) use($company){
                                    $q->where('company_id',$company);
                                })
                                ->where('status','Approved');
    }



    public function headings(): array
    {
        return [
            'USER ID',
            // 'EMPLOYEE NAME',
            'DATE',
            'MAX OVERTIME',
            'HOURS WORKED CAP SPWH',
            'COMPUTE RW OT',
            'COMPUTE RD',
            'COMPUTE SUN',
            'COMPUTE RH',
            'COMPUTE SPH',
            'REMARKS',
        ];
    }

    public function map($employee_ot): array
    {
        
        $rw_ot = $this->isRWOT($employee_ot->ot_date); //Regular Work
        $rd = $this->isRD($employee_ot->ot_date); //Rest Day
        $sun = $this->isSUN($employee_ot->ot_date); //Sunday
        $rh = $this->isRH($employee_ot->ot_date); //Regular Holiday
        $sph = $this->isSPH($employee_ot->ot_date); //Special Holiday
        // $remarks = $this->isRemarks($employee_ot->end_time); //Remarks
        $remarks = ''; //Remarks

        $ot_approved_hrs = $employee_ot->ot_approved_hrs - $employee_ot->break_hrs;

        if($rd == '1' || $sun == '1' || $rh == '1' || $sph == '1'){
            if($ot_approved_hrs > 8){
                $hours_worked_cap_spwh = 8; // Hours Cap SPWH
                $max_overtime =  $ot_approved_hrs - 8; //Max 8 Hours
                $rw_ot = 1;
            }else{
                $hours_worked_cap_spwh = $ot_approved_hrs; // Hours Cap SPWH
                $max_overtime =  0; //Max 8 Hours
            }
        }else{
            $max_overtime = $ot_approved_hrs; //Max 8 Hours
            $hours_worked_cap_spwh = 0; // Hours Cap SPWH
        }

        return [
            $employee_ot->employee->employee_number,
            // $employee_ot->user->name,
            date('d/m/Y',strtotime($employee_ot->ot_date)),
            gmdate('H:i', floor($max_overtime * 3600)),
            gmdate('H:i', floor($hours_worked_cap_spwh * 3600)),
            $rw_ot,
            $rd,
            $sun,
            $rh,
            $sph,
            $remarks
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

    public function isRD( $date ) {

        $check_day = date('D',strtotime($date));
        $check = '0';
        if ($check_day == 'Sat' || $check_day == 'Sun') {
            $check = '1';
        }else{
            $check = '0';
        }
        return $check;
    }

    public function isSUN( $date ) {

        $check_day = date('D',strtotime($date));
        $check = '0';
        if ($check_day == 'Sun') {
            $check = '1';
        }else{
            $check ='0';
        }

        return $check;
    }

    public function isRH( $date ) {
        $regular_holiday = Holiday::where('holiday_type','Legal Holiday')
                                    ->where('holiday_date',date('Y-m-d',strtotime($date)))
                                    ->first();
        $check = '0';
        if($regular_holiday){
            $check = '1';
        }else{
            $check = '0';
        }
        return $check;
    }

    public function isSPH( $date ) {
        $special_holiday = Holiday::where('holiday_type','Special Holiday')
                                    ->where('holiday_date',date('Y-m-d',strtotime($date)))
                                    ->first();

        $check = '0';
        if($special_holiday){
            $check = '1';
        }else{
            $check = '0';
        }
        return $check;
    }

    public function isRemarks($end_time){
        if(date('H:i',strtotime($end_time)) >= '13:00'){
            return 'Second Shift';
        }else{
            return 'First Shift';
        }
        
    }





}
