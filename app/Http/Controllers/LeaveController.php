<?php

namespace App\Http\Controllers;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\EmployeeApproverController;
use App\Leave;
use App\EmployeeLeave;
use App\LeaveBalance;
use App\Employee;
use App\Company;
use Illuminate\Http\Request;

use App\Exports\EmployeeLeaveExport;
use Excel;

use RealRashid\SweetAlert\Facades\Alert;

class LeaveController extends Controller
{
    //
    public function leaves()
    {
        return $leave_types = Leave::get();
        return view(
            'forms.leaves.leaves',
            array(
                'header' => 'forms',
                'leave_types' => $leave_types,
            )
        );
    }

    public function leaveDetails()
    {
        $leave_types = Leave::get();
        return view(
            'leaves.leave_types',
            array(
                'header' => 'settings',
                'leave_types' => $leave_types,
            )
        );
    }

    public function store(Request $request)
    {
        $new_leave = new Leave;
        $new_leave->leave_type = $request->leave_type;
        $new_leave->code = $request->code;
        $new_leave->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }

    public function edit($id)
    {
        $leave = Leave::where('id',$id)->get();
        return view(
            'leaves.edit',
            array(
                'header' => 'settings',
                'leave' => $leave,
            )
        );
    }

    public function update(Request $request, $id)
    {
        $leave = Leave::where('id',$id)->first();
        $leave->leave_type = $request->leave_type;
        $leave->code = $request->code;
        $leave->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }

    public function leave_report(Request $request)
    {   
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        $company = isset($request->company) ? $request->company : "";
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        $status =  isset($request->status) ? $request->status : "";
        $employee_leaves = [];
        if(isset($request->from) && isset($request->to)){
            $employee_leaves = EmployeeLeave::with('user','leave')
                                        ->whereDate('date_from','>=',$from)
                                        ->whereDate('date_from','<=',$to)
                                        ->whereHas('employee',function($q) use($company){
                                            $q->where('company_id',$company);
                                        })
                                        ->where('status',$status)
                                        ->get();
        }
        

        return view('reports.leave_report', array(
            'header' => 'reports',
            'company'=>$company,
            'from'=>$from,
            'to'=>$to,
            'status'=>$status,
            'employee_leaves' => $employee_leaves,
            'companies' => $companies
        ));
    }

    public function export(Request $request){
        $company = isset($request->company) ? $request->company : "";
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        $status =  isset($request->status) ? $request->status : "";
        $company_detail = Company::where('id',$company)->first();
        return Excel::download(new EmployeeLeaveExport($company,$from,$to,$status), 'Leave ' . $company_detail->company_code . ' ' . $from . ' to ' . $to . '.xlsx');
    }

    public function leaveBalances()
    {
        $leave_types = Leave::get();
        $employee_leaves = EmployeeLeave::with('user','leave')->get();
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
            'leave_types' => $leave_types
        ));
    }    

    public function delete($id){
        $leave = Leave::where('id',$id)->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');

        return back();
    }


}
