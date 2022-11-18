<?php

namespace App\Http\Controllers;
use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\EmployeeOb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeObController extends Controller
{
    public function ob ()
    { 
        
        $get_approvers = new EmployeeApproverController;
        $obs = EmployeeOb::with('user')->where('user_id',auth()->user()->id)->get();
        $all_approvers = $get_approvers->get_approvers(auth()->user()->id);
        return view('forms.officialbusiness.officialbusiness',
        array(
            'header' => 'forms',
            'all_approvers' => $all_approvers,
            'obs' => $obs,
        ));

    }

    public function new(Request $request)
    {
        $new_ob = new EmployeeOb;
        $new_ob->user_id = Auth::user()->id;
        $emp = Employee::where('user_id',auth()->user()->id)->first();
        $new_ob->schedule_id = $emp->schedule_id;
        $new_ob->date_from = $request->date_from;
        $new_ob->date_to = $request->date_to;
        $new_ob->remarks = $request->remarks;
        $new_ob->destination = $request->destination;
        $new_ob->persontosee = $request->persontosee;
        $logo = $request->file('attachment');
        $original_name = $logo->getClientOriginalName();
        $name = time() . '_' . $logo->getClientOriginalName();
        $logo->move(public_path() . '/images/', $name);
        $file_name = '/images/' . $name;
        $new_ob->attachment = $file_name;
        $new_ob->status = 'Pending';
        $new_ob->level = 1;
        $new_ob->created_by = Auth::user()->id;
        $new_ob->save();
    
        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    } 
    

    public function edit_ob(Request $request, $id)
    {
        $new_ob = EmployeeOb::findOrFail($id);
        $new_ob->user_id = Auth::user()->id;
        $new_ob->date_from = $request->date_from;
        $new_ob->date_to = $request->date_to;
        $new_ob->remarks = $request->remarks;
        $new_ob->destination = $request->destination;
        $new_ob->persontosee = $request->persontosee;
        $logo = $request->file('attachment');
        if(isset($logo)){
            $original_name = $logo->getClientOriginalName();
            $name = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path() . '/images/', $name);
            $file_name = '/images/' . $name;
            $new_ob->attachment = $file_name;
        }
        $new_ob->status = 'Pending';
        $new_ob->level = 1;
        $new_ob->created_by = Auth::user()->id;
        $new_ob->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }    

    public function disable_ob($id)
    {
        EmployeeOb::Where('id', $id)->update(['status' => 'Cancelled']);
        Alert::success('Official Business has been cancelled.')->persistent('Dismiss');
        return back();
    }       
}
