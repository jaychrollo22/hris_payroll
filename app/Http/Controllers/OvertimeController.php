<?php

namespace App\Http\Controllers;
use App\Http\Controllers\EmployeeApproverController;
use App\Employee;
use App\Overtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class OvertimeController extends Controller
{
    //

    public function overtime ()
    { 
        
        $get_approvers = new EmployeeApproverController;
        $overtimes = Overtime::with('user')->get();
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
        $new_overtime = new Overtime;
        $new_overtime->user_id = Auth::user()->id;
        $new_overtime->ot_date = $request->date_from;
        $new_overtime->start_time = $request->start_time;
        $new_overtime->end_time = $request->end_time;
        $new_overtime->remarks = $request->remarks;
        $new_overtime->attachment = $request->attachment;
        $new_overtime->status = 'Active';
        $new_overtime->level = 1;
        $new_overtime->created_by = Auth::user()->id;
        $new_overtime->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }
}
