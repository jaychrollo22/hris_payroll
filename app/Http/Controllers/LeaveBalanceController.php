<?php

namespace App\Http\Controllers;
use App\LeaveBalance;
use Illuminate\Http\Request;

class LeaveBalanceController extends Controller
{

    public function get_leave_balances($id)
    {

        $leavebalances = LeaveBalance::with('leave')
        ->where('emp_code',$id)
        ->get();

        return $leavebalances;
    }
}
