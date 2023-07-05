<?php

namespace App\Http\Controllers;
use App\Http\Controllers\AttendanceController;
use Illuminate\Http\Request;
use App\Handbook;
use App\Employee;
use App\Announcement;
use App\ScheduleData;
use App\Holiday;

use App\EmployeeLeave;
use App\EmployeeOvertime;
use App\EmployeeWfh;
use App\EmployeeOb;
use App\EmployeeDtr;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $schedules = [];
        $attendance_controller = new AttendanceController;
        $sevendays = date('Y-m-d',strtotime("-7 days"));
        if(auth()->user()->employee){
            if(auth()->user()->employee->employee_number){
                $attendance_now = $attendance_controller->get_attendances(date('Y-m-d'),date('Y-m-d'),auth()->user()->employee->employee_number)->first();
                $attendances = $attendance_controller->get_attendances($sevendays,date('Y-m-d',strtotime("-1 day")),auth()->user()->employee->employee_number);
            }else{
                $attendance_now = null;
                $attendances = null;
            }

            $schedules = ScheduleData::where('schedule_id',auth()->user()->employee->schedule_id)->get();
        }else{
            $attendance_now = null;
            $attendances = null;
        }
        
        // dd($attendances->unique('time_in','Y-m-d'));
        $date_ranges = $attendance_controller->dateRange($sevendays,date('Y-m-d',strtotime("-1 day")));
        $handbook = Handbook::orderBy('id','desc')->first();
        $employees_under = auth()->user()->subbordinates;
        // dd(auth()->user()->employee);
        $attendance_employees = $attendance_controller->get_attendances_employees(date('Y-m-d'),date('Y-m-d'),$employees_under->pluck('employee_number')->toArray());
        // dd($attendance_employees);
        $announcements = Announcement::with('user')->where('expired',null)
        ->orWhere('expired',">=",date('Y-m-d'))->get();
        
        $birth_date_celebrants = Employee::with('department')->where('status','Active')
        ->orderBy('birth_date','asc')
        ->get();

        $holidays = Holiday::where('status','Permanent')
        ->orWhere(function ($query)
        {
            $query->where('status',null)->whereYear('holiday_date', '=', date('Y'));
        })
        ->orderBy('holiday_date','asc')->get();
        // dd($holidays);

        session([
            'pending_leave_count'=>$this->pending_leave_count(auth()->user()->id),
            'pending_overtime_count'=>$this->pending_overtime_count(auth()->user()->id),
            'pending_wfh_count'=>$this->pending_wfh_count(auth()->user()->id),
            'pending_ob_count'=>$this->pending_ob_count(auth()->user()->id),
            'pending_dtr_count'=>$this->pending_dtr_count(auth()->user()->id),
        ]);

        return view('dashboards.home',
        array(
            'header' => '',
            'date_ranges' => $date_ranges,
            'handbook' => $handbook,
            'attendance_now' => $attendance_now,
            'attendances' => $attendances,
            'birth_date_celebrants' => $birth_date_celebrants,
            'schedules' => $schedules,
            'announcements' => $announcements ,
            'attendance_employees' => $attendance_employees ,
            'holidays' => $holidays ,
        ));
    }

    public function managerDashboard()
    {

        
        $handbook = Handbook::orderBy('id','desc')->first();
        return view('dashboards.dashboard_manager',
        array(
            'header' => 'dashboard-manager',
            'handbook' => $handbook,
        ));
    }

    public function pending_leave_count($approver_id){

        $today = date('Y-m-d');
        $from_date = date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = date('Y-m-d');
    
        return EmployeeLeave::with('approver.approver_info','user')
                                    ->whereHas('approver',function($q) use($approver_id) {
                                        $q->where('approver_id',$approver_id);
                                    })
                                    ->whereDate('created_at','>=',$from_date)
                                    ->whereDate('created_at','<=',$to_date)
                                    ->where('status','Pending')
                                    ->count();
    }
    public function pending_overtime_count($approver_id){
    
        $today = date('Y-m-d');
        $from_date = date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = date('Y-m-d');
    
        return EmployeeOvertime::with('approver.approver_info','user')
                                    ->whereHas('approver',function($q) use($approver_id) {
                                        $q->where('approver_id',$approver_id);
                                    })
                                    ->whereDate('created_at','>=',$from_date)
                                    ->whereDate('created_at','<=',$to_date)
                                    ->where('status','Pending')
                                    ->count();
    }
    public function pending_wfh_count($approver_id){
    
        $today = date('Y-m-d');
        $from_date = date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = date('Y-m-d');
    
        return EmployeeWfh::with('approver.approver_info','user')
                                    ->whereHas('approver',function($q) use($approver_id) {
                                        $q->where('approver_id',$approver_id);
                                    })
                                    ->where('status','Pending')
                                    ->whereDate('created_at','>=',$from_date)
                                    ->whereDate('created_at','<=',$to_date)
                                    ->count();
    }
    
    public function pending_ob_count($approver_id){
    
        $today = date('Y-m-d');
        $from_date = date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = date('Y-m-d');
    
        return EmployeeOb::with('approver.approver_info','user')
                                    ->whereHas('approver',function($q) use($approver_id) {
                                        $q->where('approver_id',$approver_id);
                                    })
                                    ->where('status','Pending')
                                    ->whereDate('created_at','>=',$from_date)
                                    ->whereDate('created_at','<=',$to_date)
                                    ->count();
    }
    
    public function pending_dtr_count($approver_id){
    
        $today = date('Y-m-d');
        $from_date = date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to_date = date('Y-m-d');
    
        return EmployeeDtr::with('approver.approver_info','user')
                                    ->whereHas('approver',function($q) use($approver_id) {
                                        $q->where('approver_id',$approver_id);
                                    })
                                    ->where('status','Pending')
                                    ->whereDate('created_at','>=',$from_date)
                                    ->whereDate('created_at','<=',$to_date)
                                    ->count();
    }
}
