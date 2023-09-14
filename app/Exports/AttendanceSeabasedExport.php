<?php

namespace App\Exports;

use App\SeabasedAttendance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AttendanceSeabasedExport implements FromView
{
    public function __construct($from,$to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View 
    {
        $from_date = $this->from;
        $to_date = $this->to;

        if ($from_date != null) {
            $attendances = SeabasedAttendance::with('employee')->orderBy('time_in','asc')
                                            ->orderBy('id','asc')
                                            ->where(function($q) use ($from_date, $to_date) {
                                                $q->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                                ->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"]);
                                            })
                                            ->get();

            return view('exports.attendance_seabased_export', [
                'attendances' => $attendances,
            ]);
        }else{
            return view('exports.attendance_seabased_export', [
                'attendances' => [],
            ]);
        }

    }
}
