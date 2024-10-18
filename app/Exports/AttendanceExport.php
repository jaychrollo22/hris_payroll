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

class AttendanceExport implements FromCollection, WithHeadings
{

    public function __construct($from,$to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        $attendances = [];
        $startDate = Carbon::parse($this->from);
        $endDate = Carbon::parse($this->to);
        $employee = Employee::select('id','user_id','employee_number','first_name','last_name','schedule_id','work_description')
            ->where('employee_number', auth()->user()->employee->employee_number)
            ->where('status','Active')
            ->first();

        // Loop through each day from the start date to the end date
        while ($startDate->lte($endDate)) {
            $attendance = [];
            $attendance[] = $employee->employee_number;
            $attendance[] = $employee->first_name . ' ' . $employee->last_name;
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
            'USER ID', 'NAME', 'SCHEDULE', 'DATE', 'DAY', 'TIME IN','TIME OUT','WORK','LATES',
            'UNDERTIME','OVERTIME','APPROVED OVERTIME','NIGHT DIFF','OT NIGHT DIFF','DEVICE','REMARKS'
        ];
    }

}
