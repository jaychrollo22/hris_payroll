<?php

namespace App\Http\Controllers;

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
        return view('dashboards.home',
        array(
            'header' => '',
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
