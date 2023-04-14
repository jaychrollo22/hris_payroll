<?php

namespace App\Http\Controllers;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\ScheduleData;
use App\Leave;
use App\EmployeeLeave;
use App\EmployeeLeaveCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

use DateTime;

class EmployeeLeaveController extends Controller
{

 
    public function leaveBalances()
    {
        $used_vl = checkUsedVacationLeave(auth()->user()->id);
        $used_sl = checkUsedSickLeave(auth()->user()->id);
        $used_sil = checkUsedServiceIncentiveLeave(auth()->user()->id);

        $earned_vl = checkEarnedLeave(auth()->user()->id,1);
        $earned_sl = checkEarnedLeave(auth()->user()->id,2);
        $earned_sil = checkEarnedLeave(auth()->user()->id,10);


        $employee_status = Employee::select('original_date_hired','classification','gender')->where('user_id',auth()->user()->id)->first();
        $leave_types = Leave::all(); //masterfile
        $employee_leaves = EmployeeLeave::with('user','leave','schedule')->where('user_id',auth()->user()->id)->get();
        $get_leave_balances = new LeaveBalanceController;
        $get_approvers = new EmployeeApproverController;
        $leave_balances = EmployeeLeaveCredit::with('leave')->where('user_id',auth()->user()->id)->get();
        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
        
        $allowed_to_file = true;
        //Validate Project Based
        if($employee_status->classification == '3' || $employee_status->classification == '5'){
            $date_from = new DateTime($employee_status->original_date_hired);
            $date_diff = $date_from->diff(new DateTime(date('Y-m-d')));
            
            if($date_diff->format('%m') > 5){
                $allowed_to_file = true;
            }else{
                $allowed_to_file = false;
            }
        }

        return view('forms.leaves.leaves',
        array(
            'header' => 'forms',
            'leave_balances' => $leave_balances,
            'all_approvers' => $all_approvers,
            'employee_leaves' => $employee_leaves,
            'leave_types' => $leave_types,
            'employee_status' => $employee_status,
            'used_vl' => $used_vl,
            'used_sl' => $used_sl,
            'used_sil' => $used_sil,
            'earned_vl' => $earned_vl,
            'earned_sl' => $earned_sl,
            'earned_sil' => $earned_sil,
            'allowed_to_file' => $allowed_to_file,
        ));
    }  


    public function new(Request $request)
    {
        $new_leave = new EmployeeLeave;

        $new_leave->user_id = Auth::user()->id;
        $emp = Employee::where('user_id',auth()->user()->id)->first();
        $new_leave->schedule_id = $emp->schedule_id;
        $new_leave->leave_type = $request->leave_type;
        $new_leave->date_from = $request->date_from;
        $new_leave->date_to = $request->date_to;
        $new_leave->reason = $request->reason;
        $new_leave->withpay = (isset($request->withpay)) ? $request->withpay : 0 ;
        $new_leave->halfday = (isset($request->halfday)) ? $request->halfday : 0 ; 
        $new_leave->halfday_status = $request->halfday == '1' && (isset($request->halfday_status)) ? $request->halfday_status : "" ; 

        if($request->file('attachment')){
            $logo = $request->file('attachment');
            $original_name = $logo->getClientOriginalName();
            $name = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path() . '/images/', $name);
            $file_name = '/images/' . $name;
            $new_leave->attachment = $file_name;
        }
        
        $new_leave->status = 'Pending';
        $new_leave->level = 0;
        $new_leave->created_by = Auth::user()->id;
        $new_leave->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }


    public function edit_leave(Request $request, $id)
    {
        $new_leave = EmployeeLeave::findOrFail($id);
        $new_leave->user_id = Auth::user()->id;
        $new_leave->leave_type = $request->leave_type;
        $new_leave->date_from = $request->date_from;
        $new_leave->date_to = $request->date_to;
        $new_leave->reason = $request->reason;
        $new_leave->withpay = (isset($request->withpay)) ? $request->withpay : 0 ;
        $new_leave->halfday = (isset($request->halfday)) ? $request->halfday : 0 ; 
        $new_leave->halfday_status = $request->halfday == '1' && (isset($request->halfday_status)) ? $request->halfday_status : ""; 

        $logo = $request->file('attachment');
        if(isset($logo)){
            $original_name = $logo->getClientOriginalName();
            $name = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path() . '/images/', $name);
            $file_name = '/images/' . $name;
            $new_leave->attachment = $file_name;
        }
        $new_leave->status = 'Pending';
        $new_leave->level = 0;
        $new_leave->created_by = Auth::user()->id;
        $new_leave->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function disable_leave($id)
    {
        EmployeeLeave::Where('id', $id)->update(['status' => 'Cancelled']);
        Alert::success('Leave has been cancelled.')->persistent('Dismiss');
        return back();
        
    }

        
}
