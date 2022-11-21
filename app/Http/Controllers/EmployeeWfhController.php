<?php

namespace App\Http\Controllers;
use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\EmployeeWfh;
use App\WfhTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeWfhController extends Controller
{
   

    public function wfh ()
    { 
        
        $get_approvers = new EmployeeApproverController;
        $wfhs = EmployeeWfh::with('user','schedule')->where('user_id',auth()->user()->id)->get();
        $tasks = WfhTask::with('task')->get();
        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
        return view('forms.wfh.wfh',
        array(
            'header' => 'forms',
            'all_approvers' => $all_approvers,
            'tasks' => $tasks,
            'wfhs' => $wfhs,
        ));

    }

    public function new(Request $request)
    {
        $new_wfh = new EmployeeWfh;
        $new_wfh->user_id = Auth::user()->id;
        $emp = Employee::where('user_id',auth()->user()->id)->first();
        $new_wfh->schedule_id = $emp->schedule_id;
        $new_wfh->date_from = $request->date_from;
        $new_wfh->date_to = $request->date_to;
        $new_wfh->remarks = $request->remarks;
        $logo = $request->file('attachment');
        $original_name = $logo->getClientOriginalName();
        $name = time() . '_' . $logo->getClientOriginalName();
        $logo->move(public_path() . '/images/', $name);
        $file_name = '/images/' . $name;
        $new_wfh->attachment = $file_name;
        $new_wfh->status = 'Pending';
        $new_wfh->level = 1;
        $new_wfh->created_by = Auth::user()->id;
        $new_wfh->save();
  
        foreach($request->task as $task)
        {
            $new_wfh_task = new WfhTask();
            $new_wfh_task->wfh_id = $new_wfh->id;
            $new_wfh_task->task_desc = $task;
            $new_wfh_task->save();
        }        

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }   
    

    public function edit_overtime(Request $request, $id)
    {
        $new_overtime = EmployeeWfh::findOrFail($id);
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
        EmployeeWfh::Where('id', $id)->update(['status' => 'Cancelled']);
        Alert::success('Overtime has been cancelled.')->persistent('Dismiss');
        return back();
    }       
}
