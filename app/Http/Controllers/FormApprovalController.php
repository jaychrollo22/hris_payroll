<?php

namespace App\Http\Controllers;
use App\EmployeeLeave;
use App\EmployeeWfh;
use App\EmployeeOvertime;
use App\EmployeeOb;
use App\EmployeeDtr;
use App\EmployeeApprover;
use Illuminate\Http\Request;

class FormApprovalController extends Controller
{
    public function form_approval ()
    { 
        $leaves = EmployeeLeave::with('approver')->get();
        dd($leaves);
        // $form_approvals = EmployeeApprover::with(['user_info.emp_leave','user_info.emp_ot','user_info.emp_wfh','user_info.emp_ob','user_info.emp_dtr','user_info.approvers'])
        // ->where('approver_id',auth()->user()->id)->get();
        return view('for-approval.leave-approval',
        array(
            'header' => 'for-approval',
            'form_approvals' => $leaves,
        ));

    }
}
