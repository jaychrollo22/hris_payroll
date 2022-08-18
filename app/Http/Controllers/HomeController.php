<?php

namespace App\Http\Controllers;
use App\Http\Controllers\AttendanceController;
use Illuminate\Http\Request;
use App\Handbook;
use App\Employee;
use App\ScheduleData;

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

        $attendance_controller = new AttendanceController;
        $sevendays = date('Y-m-d',strtotime("-7 days"));
        $attendance_now = $attendance_controller->get_attendances(date('Y-m-d'),date('Y-m-d'))->first();
        $attendances = $attendance_controller->get_attendances($sevendays,date('Y-m-d',strtotime("-1 day")));
        $date_ranges = $attendance_controller->dateRange($sevendays,date('Y-m-d',strtotime("-1 day")));
        $handbook = Handbook::orderBy('id','desc')->first();
        // dd(date('m'));
        $birth_date_celebrants = Employee::with('department')->where('status','Active')
        ->whereMonth('birth_date','=',date('m'))
        ->get();
        // dd($birth_date_celebrants);
        $schedules = ScheduleData::where('schedule_id',auth()->user()->employee->schedule_id)->get();
        return view('dashboards.home',
        array(
            'header' => '',
            'date_ranges' => $date_ranges,
            'handbook' => $handbook,
            'attendance_now' => $attendance_now,
            'attendances' => $attendances,
            'birth_date_celebrants' => $birth_date_celebrants,
            'schedules' => $schedules,
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
}
