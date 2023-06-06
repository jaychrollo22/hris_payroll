<?php

namespace App\Http\Controllers;
use App\IclockTransation;
use App\Attendance;
use App\Employee;
use App\PersonnelEmployee;
use App\Company;
use App\ScheduleData;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Exports\AttedancePerCompanyExport;
use Excel;

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
        $schedules = ScheduleData::all();
        // dd($attendances);

        $emp_data = Employee::with(['attendances' => function ($query) use ($from_date, $to_date) {
                                    $query->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                    ->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                    ->orderBy('time_in','asc')
                                    ->orderby('time_out','desc')
                                    ->orderBy('id','asc');
                            }])
                            ->where('employee_number', auth()->user()->employee->employee_number)
                            ->where('status','Active')
                            ->get();

        return view('attendances.view_attendance',
        array(
            'header' => 'attendances',
            'from_date' => $from_date,
            'to_date' => $to_date,
            'date_range' => $date_range,
            'attendances' => $attendances,
            'schedules' => $schedules,
            'emp_data' => $emp_data,
        ));
    }
    public function subordinates(Request $request)
    {
        $attendance_controller = new AttendanceController; 
        $from_date = $request->from;
        $to_date = $request->to;
        $date_range =  [];
        $attendances = [];
        $schedules = [];
        $emp_code = $request->employee;
        $schedule_id = null;
        $emp_data = [];
        if ($from_date != null) {
            $emp_data = Employee::with(['attendances' => function ($query) use ($from_date, $to_date) {
                                            $query->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                                    ->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                                    ->orderBy('time_in','asc')
                                                    ->orderby('time_out','desc')
                                                    ->orderBy('id','asc');
                                    }])
                                    ->whereIn('employee_number', $request->employee)
                                    ->where('status','Active')
                                    ->get();

            $date_range =  $attendance_controller->dateRange($from_date, $to_date);
           
        }
        $schedules = ScheduleData::all();
        
        return view(
            'attendances.subordinates_attendances',
            array(
                'header' => 'subordinates',
                // 'employees' => $employees,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'date_range' => $date_range,
                'attendances' => $attendances,
                'schedules' => $schedules,
                'emp_code' => $emp_code,
                'emp_data' => $emp_data,
            )
        );

        // //  
        // $from_date = $request->from;
        // $to_date = $request->to;
        // $date_range =  [];
        // $attendances = [];
        // $schedules = [];
        // if($from_date != null)
        // {
        //     $date_range =  $this->dateRange( $from_date, $to_date);
        //     $attendances =  $this->get_attendances($from_date,$to_date,$request->employee);
        //     $schedule_id = Employee::where('employee_number',$request->employee)->first();
        //     // dd($schedule_id);
        //     $schedules = ScheduleData::where('schedule_id',$schedule_id->schedule_id)->get();
        // }
    
        // // dd($attendances);
        // return view('attendances.subordinates_attendances',
        // array(
        //     'header' => 'subordinates',
        //     'from_date' => $from_date,
        //     'to_date' => $to_date,
        //     'date_range' => $date_range,
        //     'attendances' => $attendances,
        //     'schedules' => $schedules,
        // ));
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
        ->orderBy('id','asc')
        ->where(function($q) use ($from_date, $to_date) {
            $q->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])
            ->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"]);
        })
        ->get();

        return $attendances;
    }
    public function get_all_attendances($employees,$from_date,$to_date)
    {
          $employees = PersonnelEmployee::whereIn('employee_code',$employees)->get();

          return $employees;
    }
    public function get_attendances_employees($from_date,$to_date,$employees)
    {
        $attendances = Attendance::whereIn('employee_code',$employees)
        ->orderBy('time_in','asc')
        ->where(function($q) use ($from_date, $to_date) {
            $q->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])
            ->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"]);
        })
        ->get();

        return $attendances;
    }
    

    public function attendancePerCompanyExport(Request $request){

        $company = isset($request->company) ? $request->company : "";
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        $company_detail = Company::where('id',$company)->first();
        return Excel::download(new AttedancePerCompanyExport($company,$from,$to),  'Attendance Data ' . $company_detail->company_code . ' ' . $from . ' to ' . $to . '.xlsx');

    }
}
