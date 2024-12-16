<?php

namespace App\Exports;

use App\Company;
use App\Employee;
use App\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use DateTime;

class AttendanceExport implements FromCollection, WithHeadings
{

    public function __construct($from,$to)
    {
        $this->from = $from;
        $this->to = $to;
        $this->employee_number = auth()->user()->employee->employee_number;
    }

    public function collection()
    {
        $attendances = [];
        $startDate = Carbon::parse($this->from);
        $endDate = Carbon::parse($this->to);
        $time_in = "";
        $time_out = "";
        $time_difference = "";
        $work_diff_hours = "";
        $device_in = "";
        $device_out = "";

        $employee = Employee::select('id','user_id','employee_number','first_name','last_name','schedule_id','work_description')
            ->where('employee_number', $this->employee_number)
            ->where('status','Active')
            ->first();

        // Loop through each day from the start date to the end date
        while ($startDate->lte($endDate)) {
            if($a = $this->checkAttendance($startDate)){
                $time_in = date('H:i',strtotime($a->time_in));
                $time_out = date('H:i',strtotime($a->time_out));
                $device_in = "Time In: ". $a->device_in;

                $start_datetime = new DateTime($a->time_in); 
                if($a->time_out) {
                    $time_difference = $start_datetime->diff(new DateTime($a->time_out));
                    $work_diff_hours = round($time_difference->s / 3600 + $time_difference->i / 60 + $time_difference->h + $time_difference->days * 24, 2);
                    $time_out_device = $a->device_out;
                    $device_out = "Time Out: ".$a->device_out;
                }
            }

            $attendance = [];
            $attendance[] = $this->employee_number;
            $attendance[] = $employee->first_name . ' ' . $employee->last_name;
            $attendance[] = $employee->schedule_info ? $employee->schedule_info->schedule_name : '';
            $attendance[] = date('m/d/Y',strtotime($startDate));
            $attendance[] = date('l',strtotime($startDate));
            $attendance[] = $time_in;
            $attendance[] = $time_out;
            $attendance[] =  $time_difference ? $time_difference->h.' hrs.'. $time_difference->i .'mins'. '('.$work_diff_hours.')' : '';
            $attendance[] = '';//Lates
            $attendance[] = '';//Undertime
            $attendance[] = '';//Overtime
            $attendance[] = '';//Approved Overtime
            $attendance[] = '';//Night Difference
            $attendance[] = '';//OT Night Difference
            $attendance[] = $device_in;
            $attendance[] = $device_out;
            $attendance[] = '';//Remarks
            $attendances[] = $attendance;
            // Move to the next day
            $startDate->addDay();
        }

        return collect($attendances);
    }

    /**
     * Define the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'USER ID', 'NAME', 'SCHEDULE', 'DATE', 'DAY', 'TIME IN','TIME OUT','WORK','LATES','UNDERTIME',
            'OVERTIME','APPROVED OVERTIME','NIGHT DIFF','OT NIGHT DIFF','DEVICE IN','DEVICE OUT','REMARKS'
        ];
    }

    /**
     * Define the headings for the export.
     *
     * @return array
     */
    public function checkAttendance($date){
        return Attendance::whereDate('time_in',$date)
            ->where('employee_code',$this->employee_number)
            ->first();
    }
}
