<?php

namespace App\Http\Controllers;
use App\IclockTransation;
use App\Attendance;
use App\Employee;
use App\PersonnelEmployee;
use App\Company;
use App\ScheduleData;
use App\SeabasedAttendance;
use App\HikAttLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Exports\AttedancePerCompanyExport;;

use App\Exports\AttendanceSeabasedExport;
use App\Imports\EmployeeSeabasedAttendanceImport;
use App\Imports\HikAttLogAttendanceImport;

use Excel;

use RealRashid\SweetAlert\Facades\Alert;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        ini_set('memory_limit', '-1');
        
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

        $emp_data = Employee::select('id','user_id','employee_number','first_name','last_name','schedule_id')
                                ->with(['schedule_info','attendances' => function ($query) use ($from_date, $to_date) {
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
            $emp_data = Employee::select('id','user_id','employee_number','first_name','last_name','schedule_id')
                                    ->with(['schedule_info','attendances' => function ($query) use ($from_date, $to_date) {
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

    public function seabasedAttendances(Request $request){
        
        ini_set('memory_limit', '-1');

        $from_date = $request->from;
        $to_date = $request->to;

        $attendances = SeabasedAttendance::with('employee')->orderBy('time_in','asc')
                                            ->orderBy('id','asc')
                                            ->where(function($q) use ($from_date, $to_date) {
                                                $q->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])
                                                ->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"]);
                                            })
                                            ->get();

        return view('attendances.employee_seabased_attendances',
        array(
            'header' => 'attendances',
            'from_date' => $from_date,
            'to_date' => $to_date,
            'attendances' => $attendances
        )); 
    }

    public function uploadSeabasedAttendance(Request $request){
        
        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray(new EmployeeSeabasedAttendanceImport, $request->file('file'));

        if(count($data[0]) > 0)
        {
            $save_count = 0;
            $not_save = [];
            foreach($data[0] as $key => $value)
            {
                if(isset($value['attendance_date'])){

                    $check_attendace = SeabasedAttendance::where('employee_code',$value['employee_code'])
                                                            ->where('attendance_date',$value['attendance_date'])
                                                            ->where('shift',$value['shift'])
                                                            ->first();
                    if($check_attendace){
                        $attendance_date = isset($value['attendance_date']) ? ($value['attendance_date'] - 25569) * 86400 : null;
                        $attendance_date = isset($value['attendance_date']) ? gmdate("Y-m-d", $attendance_date) : null;

                        $time_in = isset($value['time_in']) ? ($value['time_in'] - 25569) * 86400 : null;
                        $time_in = isset($value['time_in']) ? gmdate("Y-m-d H:i:s", $time_in) : null;

                        $time_out = isset($value['time_out']) ? ($value['time_out'] - 25569) * 86400 : null;
                        $time_out = isset($value['time_out']) ? gmdate("Y-m-d H:i:s", $time_out) : null;

                        $check_attendace->attendance_date =  $attendance_date;
                        $check_attendace->time_in =  $time_in;
                        $check_attendace->time_out =  $time_out;
                        $check_attendace->updated_by =  auth()->user()->id;
                        $check_attendace->save();
                        $save_count++;
                    }else{
                        $new_attendance = new SeabasedAttendance;
                        $new_attendance->employee_code =  $value['employee_code'];

                        $attendance_date = isset($value['attendance_date']) ? ($value['attendance_date'] - 25569) * 86400 : null;
                        $attendance_date = isset($value['attendance_date']) ? gmdate("Y-m-d", $attendance_date) : null;

                        $time_in = isset($value['time_in']) ? ($value['time_in'] - 25569) * 86400 : null;
                        $time_in = isset($value['time_in']) ? gmdate("Y-m-d H:i:s", $time_in) : null;

                        $time_out = isset($value['time_out']) ? ($value['time_out'] - 25569) * 86400 : null;
                        $time_out = isset($value['time_out']) ? gmdate("Y-m-d H:i:s", $time_out) : null;

                        $new_attendance->attendance_date =  $attendance_date;
                        $new_attendance->time_in =  $time_in;
                        $new_attendance->time_out =  $time_out;
                        $new_attendance->shift =  $value['shift'];
                        $new_attendance->created_by =  auth()->user()->id;
                        $new_attendance->save();
                        $save_count++;
                    }

                    
                    
                }
            }
            Alert::success('Successfully Import Attendances (' . $save_count. ')')->persistent('Dismiss');
            return redirect('/seabased-attendances');
        }
    }

    public function attendanceSeabasedAttendnaceExport(Request $request){

        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";

        return Excel::download(new AttendanceSeabasedExport($from,$to),  'Attendance Data ' . $from . ' to ' . $to . '.xlsx');

    }

    public function hikAttendances(Request $request){
        
        ini_set('memory_limit', '-1');

        $from_date = $request->from ." 00:00:01";
        $to_date = $request->to ." 23:59:59";
        $terminal = $request->terminal_hik;

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_locations = getUserAllowedLocations(auth()->user()->id);
        $allowed_projects = getUserAllowedProjects(auth()->user()->id);

        $attendances = Attendance::whereBetween('created_at',[$from_date,$to_date])
                                ->where('is_upload_hik','1')
                                ->orderBy('created_at','asc')
                                ->get();

        return view('attendances.employee_hik_attendances',
        array(
            'header' => 'attendances',
            'terminal' => $from_date,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'attendances' => $attendances
        )); 
    }

    public function uploadHikAttendance(Request $request){
        
        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray(new HikAttLogAttendanceImport, $request->file('file'));

        if(count($data[0]) > 0)
        {
            $save_count = 0;
            $not_save = [];
            foreach($data[0] as $key => $value)
            {
                
                if($value['time']){

                    $person_id = str_replace("'","",$value['person_id']);
                
                    $attendance_date = isset($value['time']) ? date('Y-m-d H:i',strtotime($value['time'])) : null;

                    $direction = '';
                    if($value['attendance_status'] == 'Check-in'){
                        $direction = 'In';
                    }
                    elseif($value['attendance_status'] == 'Check-out'){
                        $direction = 'Out';
                    }

                    if($direction == 'In' || $direction == 'IN')
                    {
                        $attend = Attendance::where('employee_code',$person_id)->whereDate('time_in',date('Y-m-d', strtotime($attendance_date)))->first();
                        if($attend == null)
                        {
                            $attendance = new Attendance;
                            $attendance->employee_code  = $person_id;   
                            $attendance->time_in = date('Y-m-d H:i:s',strtotime($attendance_date));
                            $attendance->device_in = $value['attendance_check_point'];
                            $attendance->is_upload_hik = 1;
                            $attendance->save();
                            $save_count++; 
                        }
                    }
                    else if($direction == 'Out' || $direction == 'OUT' )
                    {
                        $time_in_after = date('Y-m-d H:i:s',strtotime($attendance_date));
                        $time_in_before = date('Y-m-d H:i:s', strtotime ( '-22 hour' , strtotime ( $time_in_after ) )) ;
                        
                        $update = [
                            'time_out' =>  date('Y-m-d H:i:s', strtotime($attendance_date)),
                            'device_out' => $value['attendance_check_point'],
                        ];

                        $attendance_in = Attendance::where('employee_code',$person_id)
                        ->whereBetween('time_in',[$time_in_before,$time_in_after])->first();

                        Attendance::where('employee_code',$person_id)
                        ->whereBetween('time_in',[$time_in_before,$time_in_after])
                        ->update($update);

                        if($attendance_in ==  null)
                        {
                            $attendance = new Attendance;
                            $attendance->employee_code  = $person_id;   
                            $attendance->time_out = date('Y-m-d H:i:s', strtotime($attendance_date));
                            $attendance->device_out = $value['attendance_check_point'];
                            $attendance->is_upload_hik = 1;
                            $attendance->save(); 
                            $save_count++;
                        }   
                    }
                }
                
            }

            Alert::success('Successfully Import Attendances (' . $save_count. ')')->persistent('Dismiss');
            return redirect('/hik-attendances');
           
        }
    }
}
