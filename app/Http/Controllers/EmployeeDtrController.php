<?php

namespace App\Http\Controllers;
use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\EmployeeDtr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeDtrController extends Controller
{
    public function dtr ()
    { 
        
        $get_approvers = new EmployeeApproverController;
        $dtrs = EmployeeDtr::with('user')->where('user_id',auth()->user()->id)->get();
        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
        return view('forms.dtr.dtr',
        array(
            'header' => 'forms',
            'all_approvers' => $all_approvers,
            'dtrs' => $dtrs,
        ));

    }

    public function new(Request $request)
    {
        $new_dtr = new EmployeeDtr;
        $new_dtr->user_id = Auth::user()->id;
        $new_dtr->dtr_date = $request->dtr_date;
        $emp = Employee::where('user_id',auth()->user()->id)->first();
        $new_dtr->schedule_id = $emp->schedule_id;
        $new_dtr->correction = $request->correction;
        if($request->correction == 'Both'){
            $stime = $request->time_in;
            $etime = $request->time_out;   
            $new_dtr->time_in = $request->dtr_date.' '.$request->time_in;
            $new_dtr->time_out = $request->dtr_date.' '.$request->time_out;     
            if($stime > $etime ){
                $new_dtr->time_out = date('Y-m-d', strtotime($request->dtr_date. ' + 1 day')).' '.$request->time_out;
            }             
        }else if($request->correction == 'Time-in'){
            $new_dtr->time_in = $request->dtr_date.' '.$request->time_in;
            $new_dtr->time_out = null;
        }else{
            $new_dtr->time_in = null;
            $new_dtr->time_out = $request->dtr_date.' '.$request->time_out;
        }        
        $new_dtr->remarks = $request->remarks;
        $logo = $request->file('attachment');
        $original_name = $logo->getClientOriginalName();
        $name = time() . '_' . $logo->getClientOriginalName();
        $logo->move(public_path() . '/images/', $name);
        $file_name = '/images/' . $name;
        $new_dtr->attachment = $file_name;
        $new_dtr->status = 'Pending';
        $new_dtr->level = 1;
        $new_dtr->created_by = Auth::user()->id;
        // dd($new_dtr);
        $new_dtr->save();
    
        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    } 



    public function edit_dtr(Request $request, $id)
    {
        $new_dtr = EmployeeDtr::findOrFail($id);
        $new_dtr->user_id = Auth::user()->id;
        $new_dtr->dtr_date = $request->dtr_date;
        $new_dtr->correction = $request->correction;
        if($request->correction == 'Both'){
            $stime = $request->time_in;
            $etime = $request->time_out;   
            $new_dtr->time_in = $request->dtr_date.' '.$request->time_in;
            $new_dtr->time_out = $request->dtr_date.' '.$request->time_out;     
            if($stime > $etime ){
                $new_dtr->time_out = date('Y-m-d', strtotime($request->dtr_date. ' + 1 day')).' '.$request->time_out;
            }             
        }else if($request->correction == 'Time-in'){
            $new_dtr->time_in = $request->dtr_date.' '.$request->time_in;
            $new_dtr->time_out = null;
        }else{
            $new_dtr->time_in = null;
            $new_dtr->time_out = $request->dtr_date.' '.$request->time_out;
        }       
        $new_dtr->remarks = $request->remarks;
        $logo = $request->file('attachment');
        if(isset($logo)){
            $original_name = $logo->getClientOriginalName();
            $name = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path() . '/images/', $name);
            $file_name = '/images/' . $name;
            $new_dtr->attachment = $file_name;
        }
        $new_dtr->status = 'Pending';
        $new_dtr->level = 1;
        $new_dtr->created_by = Auth::user()->id;
        // dd($new_dtr);
        $new_dtr->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }    

    public function disable_dtr($id)
    {
        EmployeeDtr::Where('id', $id)->update(['status' => 'Cancelled']);
        Alert::success('DTR Correction has been cancelled.')->persistent('Dismiss');
        return back();
    }    
}


