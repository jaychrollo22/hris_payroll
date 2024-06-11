<?php

namespace App\Http\Controllers;

use App\Leave;
use App\Employee;
use App\EmployeeLeaveCredit;
use App\Company;
use App\Department;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LeaveCreditsController extends Controller
{
    //
    public function index(Request $request){

        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();
        $departments = [];
        if($company){
            $department_companies = Employee::when($company,function($q) use($company){
                            $q->where('company_id',$company);
                        })
                        ->groupBy('department_id')
                        ->pluck('department_id')
                        ->toArray();

            $departments = Department::whereIn('id',$department_companies)->where('status','1')
                    ->orderBy('name')
                    ->get();
        }else{
            $departments = Department::where('status','1')->orderBy('name')->get();
        }
        

        $leaveCredits = EmployeeLeaveCredit::all();
        $leaveTypes = Leave::all();
        $employees_selection = Employee::whereIn('company_id',$allowed_companies)->where('status','Active')->get();

        

        $employees = Employee::with('department','company','employee_leave_credits.leave')
                                ->whereIn('company_id',$allowed_companies)
                                ->where('status','Active')
                                ->whereHas('employee_leave_credits')
                                ->when($company,function($q) use($company){
                                    $q->where('company_id',$company);
                                })
                                ->when($department,function($q) use($department){
                                    $q->where('department_id',$department);
                                })
                                ->orderBy('first_name','ASC')
                                ->get();

        return view('employee_leave_credits.index', array(
            'header' => 'masterfiles',
            'leaveCredits' => $leaveCredits,
            'leaveTypes' => $leaveTypes,
            'employees' => $employees,
            'employees_selection' => $employees_selection,
            'companies' => $companies,
            'departments' => $departments,
            'company' => $company,
            'department' => $department,
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

        Alert::success('Successfully Stored')->persistent('Dismiss');
        return back();
    }
}
