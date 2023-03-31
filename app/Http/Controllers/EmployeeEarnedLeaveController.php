<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmployeeEarnedLeave;

class EmployeeEarnedLeaveController extends Controller
{
    public function index(){

        $earned_leaves = EmployeeEarnedLeave::with('employee.company','leave_type_info')
                                                ->orderBy('user_id','ASC')
                                                ->orderBy('leave_type','ASC')
                                                ->orderBy('created_at','DESC')
                                                ->get();

        return view('employee_earned_leaves.index', array(
            'header' => 'masterfiles',
            'earned_leaves' => $earned_leaves,
        ));
    }
}
