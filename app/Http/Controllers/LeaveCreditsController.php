<?php

namespace App\Http\Controllers;

use App\Leave;
use App\Employee;
use App\EmployeeLeaveCredit;
use Illuminate\Http\Request;

class LeaveCreditsController extends Controller
{
    //
    public function index(){
        $leaveCredits = EmployeeLeaveCredit::all();
        $leaveTypes = Leave::all();
        $employees = Employee::with('department', 'payment_info', 'ScheduleData', 'immediate_sup_data', 'user_info', 'company')->get();
        return view('employee_leave_credits.index', array(
            'header' => 'masterfiles',
            'leaveCredits' => $leaveCredits,
            'leaveTypes' => $leaveTypes,
            'employees' => $employees,
        ));
    }
}
