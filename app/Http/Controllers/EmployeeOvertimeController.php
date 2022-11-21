<?php

namespace App\Http\Controllers;
use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\EmployeeOvertime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeOvertimeController extends Controller
{
    public function overtime ()
    { 
        
        $get_approvers = new EmployeeApproverController;
        $overtimes = EmployeeOvertime::with('user')->where('user_id',auth()->user()->id)->get();
        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
        return view('forms.overtime.overtime',
        array(
            'header' => 'forms',
            'all_approvers' => $all_approvers,
            'overtimes' => $overtimes,
        ));

    }


    public function new(Request $request)
    {
        $new_overtime = new EmployeeOvertime;
        $new_overtime->user_id = Auth::user()->id;
        $emp = Employee::where('user_id',auth()->user()->id)->first();
        $new_overtime->schedule_id = $emp->schedule_id;
        $new_overtime->ot_date = $request->ot_date;
        $stime = $request->start_time;
        $etime = $request->end_time;   
        $new_overtime->start_time = $request->ot_date.' '.$request->start_time;
        $new_overtime->end_time = $request->ot_date.' '.$request->end_time;     
        if($stime > $etime ){
            $new_overtime->end_time = date('Y-m-d', strtotime($request->ot_date. ' + 1 day')).' '.$request->end_time;
        }
        $new_overtime->remarks = $request->remarks;
        $logo = $request->file('attachment');
        $original_name = $logo->getClientOriginalName();
        $name = time() . '_' . $logo->getClientOriginalName();
        $logo->move(public_path() . '/images/', $name);
        $file_name = '/images/' . $name;
        $new_overtime->attachment = $file_name;
        $new_overtime->status = 'Pending';
        $new_overtime->level = 1;
        $new_overtime->created_by = Auth::user()->id;
        $new_overtime->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }

    public function edit_overtime(Request $request, $id)
    {
        $new_overtime = EmployeeOvertime::findOrFail($id);
        $new_overtime->user_id = Auth::user()->id;
        $new_overtime->ot_date = $request->ot_date;
        $stime = $request->start_time;
        $etime = $request->end_time;   
        $new_overtime->start_time = $request->ot_date.' '.$request->start_time;
        $new_overtime->end_time = $request->ot_date.' '.$request->end_time;  
        $new_overtime->remarks = $request->remarks;   
        if($stime > $etime ){
            $new_overtime->end_time = date('Y-m-d', strtotime($request->ot_date. ' + 1 day')).' '.$request->end_time;
        }
        $logo = $request->file('attachment');
        if(isset($logo)){
            $original_name = $logo->getClientOriginalName();
            $name = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path() . '/images/', $name);
            $file_name = '/images/' . $name;
            $new_overtime->attachment = $file_name;
        }
        $new_overtime->status = 'Pending';
        $new_overtime->level = 1;
        $new_overtime->created_by = Auth::user()->id;
        $new_overtime->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }    

    public function disable_overtime($id)
    {
        EmployeeOvertime::Where('id', $id)->update(['status' => 'Cancelled']);
        Alert::success('Overtime has been cancelled.')->persistent('Dismiss');
        return back();
    }    
}
