<?php

namespace App\Http\Controllers;
use App\Http\Controllers\EmployeeApproverController;
use App\Company;
use App\Employee;
use App\Overtime;
use App\EmployeeCompany;
use App\EmployeeOvertime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

use App\Exports\EmployeeOvertimeExport;
use Excel;

class OvertimeController extends Controller
{
    //
    public function overtime_report(Request $request){
        $company = $request->company;
        $from_date = $request->from;
        $to_date = $request->to;
        $status = isset($request->status) ? $request->status : "Approved";
        $date_range = '';

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        $employee_overtimes=[];
        if ($from_date != null) {
            $company_employees = Employee::where('company_id',$request->company)->pluck('user_id')->toArray();
            
            $employee_overtimes = EmployeeOvertime::with('user','employee')
                                                    ->whereDate('ot_date','>=',$from_date)
                                                    ->whereDate('ot_date','<=',$to_date)
                                                    ->whereHas('employee',function($q) use($company){
                                                        $q->where('company_id',$company);
                                                    })
                                                    ->where('status',$status)
                                                    ->get();
        }

        return view('reports.overtime_report',
        array(
            'header' => 'overtimes',
            'employee_overtimes' => $employee_overtimes,
            'companies' => $companies,
            'company' => $company,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'status' => $status,
            'date_range' => $date_range,
        ));
    }

    public function export(Request $request){
        $company = isset($request->company) ? $request->company : "";
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        $company_detail = Company::where('id',$company)->first();
        return Excel::download(new EmployeeOvertimeExport($company,$from,$to), 'Overtime ' . $company_detail->company_code . ' ' . $from . ' to ' . $to . '.xlsx');
    }

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
        $new_overtime->break_hrs = $request->break_hrs;
        $new_overtime->remarks = $request->remarks;
        $new_overtime->attachment = $request->attachment;
        $new_overtime->status = 'Active';
        $new_overtime->level = 1;
        $new_overtime->created_by = Auth::user()->id;
        $new_overtime->save();

        Alert::success('Successfully Stored')->persistent('Dismiss');
        return back();
    }
}
