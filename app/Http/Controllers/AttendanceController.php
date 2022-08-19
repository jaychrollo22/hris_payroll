<?php

namespace App\Http\Controllers;
use App\IclockTransation;
use App\Attendance;
use App\Employee;
use App\ScheduleData;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //  
        $from_date = $request->from;
        $to_date = $request->to;
        $date_range =  [];
        $attendances = [];
        if($from_date != null)
        {
        $date_range =  $this->dateRange( $from_date, $to_date);
        $attendances =  $this->get_attendances($from_date,$to_date,auth()->user()->employee->employee_number);
        }
        $schedules = ScheduleData::where('schedule_id',auth()->user()->employee->schedule_id)->get();
        // dd($attendances);
        return view('attendances.view_attendance',
        array(
            'header' => 'attendances',
            'from_date' => $from_date,
            'to_date' => $to_date,
            'date_range' => $date_range,
            'attendances' => $attendances,
            'schedules' => $schedules,
        ));
    }
    public function subordinates(Request $request)
    {
        //  
        $from_date = $request->from;
        $to_date = $request->to;
        $date_range =  [];
        $attendances = [];
        $schedules = [];
        if($from_date != null)
        {
        $date_range =  $this->dateRange( $from_date, $to_date);
        $attendances =  $this->get_attendances($from_date,$to_date,$request->employee);
        $schedule_id = Employee::where('employee_number',$request->employee)->first();
        // dd($schedule_id);
        $schedules = ScheduleData::where('schedule_id',$schedule_id->schedule_id)->get();
        }
    
        // dd($attendances);
        return view('attendances.subordinates_attendances',
        array(
            'header' => 'subordinates',
            'from_date' => $from_date,
            'to_date' => $to_date,
            'date_range' => $date_range,
            'attendances' => $attendances,
            'schedules' => $schedules,
        ));
    }
    
    public function dateRange( $first, $last, $step = '+1 day', $format = 'Y-m-d' ) {
        $dates = [];
        $current = strtotime( $first );
        $last = strtotime( $last );
    
        while( $current <= $last ) {
    
            $dates[] = date( $format, $current );
            $current = strtotime( $step, $current );
        }
    
        return $dates;
    }

    public function get_attendances($from_date,$to_date,$id)
    {
        $attendances = Attendance::where('employee_code',$id)
        ->orderBy('time_in','asc')
        ->where(function($q) use ($from_date, $to_date) {
            $q->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])
            ->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"]);
        })
        ->get();

        return $attendances;
    }
    public function get_attendances_employees($from_date,$to_date,$employees){
        $attendances = Attendance::whereIn('employee_code',$employees)
        ->orderBy('time_in','asc')
        ->where(function($q) use ($from_date, $to_date) {
            $q->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])
            ->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"]);
        })
        ->get();

        return $attendances;

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
