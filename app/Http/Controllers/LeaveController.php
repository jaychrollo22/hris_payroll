<?php

namespace App\Http\Controllers;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\EmployeeApproverController;
use App\Leave;
use App\EmployeeLeave;
use App\LeaveBalance;
use App\Employee;
use Illuminate\Http\Request;

use App\Exports\EmployeeLeaveExport;
use Excel;

class LeaveController extends Controller
{
    //
    public function leaves()
    {
        $leave_types = Leave::get();
        return view(
            'forms.leaves.leaves',
            array(
                'header' => 'forms',
                'leave_types' => $leave_types,
            )
        );
    }
    public function leaveDetails()
    {
        $leave_types = Leave::get();
        return view(
            'leaves.leave_types',
            array(
                'header' => 'Handbooks',
                'leave_types' => $leave_types,
            )
        );
    }
    public function leave_report(Request $request)
    {
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        $employee_leaves = [];
        if(isset($request->from) && isset($request->to)){
            $employee_leaves = EmployeeLeave::with('user','leave')
                                        ->whereDate('approved_date','>=',$from)
                                        ->whereDate('approved_date','<=',$to)
                                        ->where('status','Approved')
                                        ->get();
        }
        

        return view('reports.leave_report', array(
            'header' => 'reports',
            'from'=>$from,
            'to'=>$to,
            'employee_leaves' => $employee_leaves
        ));
    }

    public function export(Request $request){
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        return Excel::download(new EmployeeLeaveExport($from,$to), 'Leave Export.xlsx');
    }

    public function leaveBalances()
    {
        $leave_types = Leave::get();
        $employee_leaves = EmployeeLeave::with('user','leave')->get();
        $get_leave_balances = new LeaveBalanceController;
        $get_approvers = new EmployeeApproverController;
        $leave_balances = $get_leave_balances->get_leave_balances(auth()->user()->employee->id);
        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);

        return view('forms.leaves.leaves',
        array(
            'header' => 'forms',
            'leave_balances' => $leave_balances,
            'all_approvers' => $all_approvers,
            'employee_leaves' => $employee_leaves,
            'leave_types' => $leave_types
        ));
    }    


}
