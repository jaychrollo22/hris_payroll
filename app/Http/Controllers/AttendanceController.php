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
use App\HikVisionAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\AttendanceLog;

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

        $emp_data = Employee::select('id','user_id','employee_number','first_name','last_name','schedule_id','location')
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
        // ->orderBy('id','asc')
        ->where(function($q) use ($from_date, $to_date) {
            $q->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"])
            ->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"]);
        })
        ->get();

        return $attendances;
    }
    public function get_attendance_now($id)
    {
        $attendances = Attendance::where('employee_code',$id)
        ->orderBy('time_in','asc')
        ->orderBy('id','asc')
        ->where(function($q){
            $q->whereDate('time_in', date('Y-m-d'));
        })
        ->first();

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
            $q->whereBetween('time_in', [$from_date." 00:00:01", $to_date." 23:59:59"]);
            // ->orWhereBetween('time_out', [$from_date." 00:00:01", $to_date." 23:59:59"]);
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
                // return $value;
                if(isset($value['attendance_date'])){

                    $check_attendace = SeabasedAttendance::where('employee_code',$value['employee_code'])
                                                            ->where('attendance_date',date('Y-m-d',strtotime($value['attendance_date'])))
                                                            ->where('shift',$value['shift'])
                                                            ->first();
                    if($check_attendace){
                    
                        $time_in = $this->convertTime($value['time_in']);
                        $time_out = $this->convertTime($value['time_out']);

                        $check_attendace->attendance_date =  date('Y-m-d',strtotime($value['attendance_date']));
                        $check_attendace->time_in =  date('Y-m-d',strtotime($value['attendance_date'])) . ' ' . $time_in;
                        $check_attendace->time_out =  date('Y-m-d',strtotime($value['attendance_date'])) . ' ' .$time_out;
                        $check_attendace->updated_by =  auth()->user()->id;
                        $check_attendace->save();
                        $save_count++;

                    }else{
                        $new_attendance = new SeabasedAttendance;
                        $new_attendance->employee_code =  $value['employee_code'];
                        $time_in = $this->convertTime($value['time_in']);
                        $time_out = $this->convertTime($value['time_out']);

                        $new_attendance->attendance_date =  date('Y-m-d',strtotime($value['attendance_date']));
                        $new_attendance->time_in =  date('Y-m-d',strtotime($value['attendance_date'])) . ' ' .$time_in;
                        $new_attendance->time_out = date('Y-m-d',strtotime($value['attendance_date'])) . ' ' .$time_out;
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

    public function convertTime($time){
        $decimalValue = $time;

        // Calculate total minutes in a day
        $totalMinutesInADay = 24 * 60;

        // Calculate the total minutes for the given decimal fraction of a day
        $totalMinutes = $decimalValue * $totalMinutesInADay;

        // Convert minutes to hours and minutes
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        // Format the result as H:i (hours and minutes)
        return $timeFormatted = sprintf("%02d:%02d", $hours, $minutes);

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

        $attendances = HikVisionAttendance::whereBetween('attendance_date',[$from_date,$to_date])
                                ->orderBy('created_at','asc')
                                ->get();

        return view('attendances.employee_hik_attendances',
        array(
            'header' => 'attendances',
            'from_date' => $request->from,
            'to_date' => $request->to,
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

            $start_date = '';
            $end_date = '';
            $is_first = true;

            foreach($data[0] as $key => $value)
            {
                
                if($value['time']){

                    
                    $person_id = str_replace("'","",$value['person_id']);               
                    $attendance_date = isset($value['time']) ? date('Y-m-d H:i',strtotime($value['time'])) : null;

                    if($is_first){
                        $is_first = false;
                        $start_date = date('Y-m-d',strtotime($attendance_date));
                    }

                    $direction = '';
                    if($value['attendance_status'] == 'Check-in'){
                        $direction = 'In';
                    }
                    elseif($value['attendance_status'] == 'Check-out'){
                        $direction = 'Out';
                    }
                    
                    $check_attendace = HikVisionAttendance::select('id')
                                                            ->where('employee_code',$person_id)
                                                            ->where('attendance_date',$attendance_date)
                                                            ->where('direction',$direction)
                                                            ->first();
                    if(empty($check_attendace)){
                        $new_attendance = new HikVisionAttendance;
                        $new_attendance->employee_code = $person_id;   
                        $new_attendance->attendance_date = $attendance_date;
                        $new_attendance->direction = $direction;
                        $new_attendance->device = $value['attendance_check_point'];
                        $new_attendance->save();
                        $save_count++;

                        $end_date = date('Y-m-d',strtotime($attendance_date));
                    }
                    

                }
                
            }

            Alert::success('Successfully Import Attendances (' . $save_count. ')')->persistent('Dismiss');
            return redirect('/hik-attendances?from='.$start_date.'&to='.$end_date);
           
        }
    }
    public function store_logs(Request $request)
    {
        ini_set('memory_limit', '-1');
        $attendance = [];
       foreach($request->data as $req)
       {
            
            $attendance = new AttendanceLog;
            $attendance->emp_code = $req['id'];
            $attendance->date = date('Y-m-d',strtotime($req['timestamp']));
            $attendance->datetime = $req['timestamp'];
            $attendance->type = $req['type'];
            $attendance->location = $request->location;
            $attendance->ip_address = $request->ip_address;
            $attendance->save();
       }
       
       if($attendance->id != null)
       {
       return array( 'code' => 200,
        'attendance' => $attendance,
        'message' => 'success',
        );
       }
       else
       {
        return array( 'code' => 500,
        'attendance' => $attendance,
        'message' => 'error',
        );
       }
    }
    public function store_logs_hk(Request $request)
    {
        ini_set('memory_limit', '-1');
        if(!empty($request->data))
        {
            foreach($request->data as $req)
            {
                 if($req['time_input'] != '00:00:00')
                 {
     
                     $attendance = new AttendanceLog;
                     $attendance->last_id = $req['id'];
                     $attendance->emp_code = $req['id_bio'];
                     $attendance->date = date('Y-m-d',strtotime($req['date_time']));
                     $attendance->datetime = $req['date_time'];
                     if($req['device_name'] == "HO IN")
                     {
                         $attendance->type = 0;
                     }
                     else
                     {
                         $attendance->type = 1;
                     }
                     $attendance->location = $request->location;
                     $attendance->ip_address = $request->ip_address;
                     $attendance->save();
                 }
                 
            }
            
            if($attendance->id != null)
            {
            return array( 'code' => 200,
             'attendance' => $attendance,
             'message' => 'success',
             );
            }
            else
            {
             return array( 'code' => 500,
             'attendance' => $attendance,
             'message' => 'error',
             );
            }
        }
      
    }
    public function getlastId($company)
    {
        $attendance = AttendanceLog::Where('ip_address',$company)->orderBy('datetime','desc')->first();
        // $id = $attendance->last_id;
        if($attendance != null)
        {
            return array('id' => $attendance->datetime);
        }
        return array('id' => 0);
    
    }
    public function getlastIdHK($company)
    {
        $attendance = AttendanceLog::Where('ip_address',$company)->orderBy('last_id','desc')->first();
        // $id = $attendance->last_id;
        if($attendance != null)
        {
            return array('id' => $attendance->last_id);
        }
        return array('id' => 0);
    
    }
    public function devices()
    {
        ini_set('memory_limit', '-1');
        $devices = AttendanceLog::with('attendance')->groupBy('ip_address')->select('ip_address')->get();

        return view('attendances.devices',
        array(
            'devices' => $devices,
        )
        );
    }
}
