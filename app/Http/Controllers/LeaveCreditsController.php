<?php

namespace App\Http\Controllers;

use App\Leave;
use App\Employee;
use App\EmployeeLeaveCredit;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LeaveCreditsController extends Controller
{
    //
    public function index(){

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $leaveCredits = EmployeeLeaveCredit::all();
        $leaveTypes = Leave::all();
        $employees_selection = Employee::whereIn('company_id',$allowed_companies)->where('status','Active')->get();

         $employees = Employee::with('department','company','employee_leave_credits.leave')
                                ->whereIn('company_id',$allowed_companies)
                                ->where('status','Active')
                                ->whereHas('employee_leave_credits')
                                ->orderBy('first_name','ASC')
                                ->get();

        return view('employee_leave_credits.index', array(
            'header' => 'masterfiles',
            'leaveCredits' => $leaveCredits,
            'leaveTypes' => $leaveTypes,
            'employees' => $employees,
            'employees_selection' => $employees_selection,
        ));
    }

    public function store(Request $request){

        $this->validate($request, [
            'leave_type' => 'required',
            'user_id' => 'required',
            'count' => 'required',
        ]);


        $leave_credit = EmployeeLeaveCredit::where('user_id',$request->user_id)
                                            ->where('leave_type',$request->leave_type)
                                            ->first();
        if($leave_credit){
            $leave_credit->count = $request->count;
            $leave_credit->save();
        }else{
            $leave_credit = new EmployeeLeaveCredit;
            $leave_credit->leave_type = $request->leave_type;
            $leave_credit->user_id = $request->user_id;
            $leave_credit->count = $request->count;
            $leave_credit->save();
        }

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }
}
