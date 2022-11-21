<?php

namespace App\Http\Controllers;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\ScheduleData;
use App\Leave;
use App\EmployeeLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeLeaveController extends Controller
{

 
    public function leaveBalances()
    {
        $leave_types = Leave::all(); //masterfile
        $employee_leaves = EmployeeLeave::with('user','leave','schedule')->where('user_id',auth()->user()->id)->get();
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
            'leave_types' => $leave_types,
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
        $logo = $request->file('attachment');
        $original_name = $logo->getClientOriginalName();
        $name = time() . '_' . $logo->getClientOriginalName();
        $logo->move(public_path() . '/images/', $name);
        $file_name = '/images/' . $name;
        $new_leave->attachment = $file_name;
        $new_leave->status = 'Pending';
        $new_leave->level = 1;
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
        $logo = $request->file('attachment');
        if(isset($logo)){
            $original_name = $logo->getClientOriginalName();
            $name = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path() . '/images/', $name);
            $file_name = '/images/' . $name;
            $new_leave->attachment = $file_name;
        }
        $new_leave->status = 'Pending';
        $new_leave->level = 1;
        $new_leave->created_by = Auth::user()->id;
        // dd($new_leave);
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
