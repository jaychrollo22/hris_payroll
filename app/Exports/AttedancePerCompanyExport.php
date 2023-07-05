<?php

namespace App\Exports;

use App\Employee;
use App\PersonnelEmployee;
use App\EmployeeCompany;
use App\ScheduleData;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AttedancePerCompanyExport implements FromView
{
    public function __construct($company,$from,$to)
    {
        $this->company = $company;
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        $from_date = $this->from;
        $to_date = $this->to;
        $company = $this->company;
        if ($from_date != null) {
            $attendances = Employee::select('employee_number','user_id','first_name','last_name','location','schedule_id')
                                        ->with(['attendances' => function ($query) use ($from_date, $to_date) {
                                            $query->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                                    ->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                                    ->orderBy('time_in','asc')
                                                    ->orderby('time_out','desc')
                                                    ->orderBy('id','asc');
                                            }])
                                            ->where('company_id', $company)
                                            ->where('status','Active')
                                            ->get();
            $date_range =  dateRange($from_date, $to_date);
            $schedules = ScheduleData::where('schedule_id', 1)->get();
            return view('exports.attendance_company_export', [
                'attendances' => $attendances,
                'date_range' => $date_range,
                'schedules' => $schedules,

            ]);
        }
    }

}
