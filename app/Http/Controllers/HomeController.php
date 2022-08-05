<?php

namespace App\Http\Controllers;
use App\Http\Controllers\AttendanceController;
use Illuminate\Http\Request;

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
        $sevendays = date('Y-m-d',strtotime("-5 days"));
        $date_ranges = $attendance_controller->dateRange($sevendays,date('Y-m-d',strtotime("-1 day")));
        return view('dashboards.home',
        array(
            'header' => '',
            'date_ranges' => $date_ranges,
        ));
    }

    public function managerDashboard()
    {

        

        return view('dashboards.dashboard_manager',
        array(
            'header' => 'dashboard-manager',
        ));
    }
}
