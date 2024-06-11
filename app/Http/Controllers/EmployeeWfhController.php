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
   

    public function wfh(Request $request)
    { 
        $today = date('Y-m-d');
        $from = isset($request->from) ? $request->from : date('Y-m-d',(strtotime ( '-1 month' , strtotime ( $today) ) ));
        $to = isset($request->to) ? $request->to : date('Y-m-d');
        $status = isset($request->status) ? $request->status : 'Pending';
        
        $get_approvers = new EmployeeApproverController;
        $wfhs = EmployeeWfh::with('user','schedule')
                                ->where('user_id',auth()->user()->id)
                                ->where('status',$status)
                                ->whereDate('created_at','>=',$from)
                                ->whereDate('created_at','<=',$to)
                                ->orderBy('created_at','DESC')
                                ->get();

        $wfhs_all = EmployeeWfh::with('user','schedule')
                                ->where('user_id',auth()->user()->id)
                                ->get();

        $tasks = WfhTask::with('task')->get();
        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
        return view('forms.wfh.wfh',
        array(
            'header' => 'forms',
            'all_approvers' => $all_approvers,
            'tasks' => $tasks,
            'wfhs' => $wfhs,
            'wfhs_all' => $wfhs_all,
            'from' => $from,
            'to' => $to,
            'status' => $status,
        ));

    }

    public function new(Request $request)
    {
        $new_wfh = new EmployeeWfh;
        $new_wfh->user_id = Auth::user()->id;
        $emp = Employee::where('user_id',auth()->user()->id)->first();
        $new_wfh->schedule_id = $emp->schedule_id;
        $new_wfh->applied_date = $request->applied_date;
        $new_wfh->date_from = $request->date_from;
        $new_wfh->date_to = $request->date_to;
        $new_wfh->remarks = $request->remarks;
        if($request->file('attachment')){
            $logo = $request->file('attachment');
            $original_name = $logo->getClientOriginalName();
            $name = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path() . '/images/', $name);
            $file_name = '/images/' . $name;
            $new_wfh->attachment = $file_name;
        }
        
        $new_wfh->status = 'Pending';
        $new_wfh->level = 0;
        $new_wfh->created_by = Auth::user()->id;
        $new_wfh->save();
  
        // foreach($request->task as $task)
        // {
        //     $new_wfh_task = new WfhTask();
        //     $new_wfh_task->wfh_id = $new_wfh->id;
        //     $new_wfh_task->task_desc = $task;
        //     $new_wfh_task->save();
        // }        

        Alert::success('Successfully Stored')->persistent('Dismiss');
        return back();
    }   
    

    public function edit_wfh(Request $request, $id)
    {
        $wfh = EmployeeWfh::findOrFail($id);
        $wfh->user_id = auth()->user()->id;
        
        $emp = Employee::where('user_id',auth()->user()->id)->first();
        $wfh->schedule_id = $emp->schedule_id;
        $wfh->applied_date = $request->applied_date;
        $wfh->date_from = $request->date_from;
        $wfh->date_to = $request->date_to;
        $wfh->remarks = $request->remarks;

        $logo = $request->file('attachment');
        if(isset($logo)){
            $original_name = $logo->getClientOriginalName();
            $name = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path() . '/images/', $name);
            $file_name = '/images/' . $name;
            $wfh->attachment = $file_name;
        }
        $wfh->status = 'Pending';
        $wfh->level = 0;
        $wfh->created_by = auth()->user()->id;
        $wfh->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }    

    public function disable_wfh($id)
    {
        EmployeeWfh::Where('id', $id)->update(['status' => 'Cancelled']);
        Alert::success('WFH has been cancelled.')->persistent('Dismiss');
        return back();
    }      
}
