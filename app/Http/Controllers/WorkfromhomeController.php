<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\EmployeeWfh;
use App\Company;

use App\Exports\EmployeeWfhExport;
use Excel;

class WorkfromhomeController extends Controller
{
    //

    public function workfromhome()
    {
        return view('forms.wfh.wfh',
        array(
            'header' => 'forms',
        ));
    }

    public function wfh_report(Request $request)
    {   
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        $company = isset($request->company) ? $request->company : "";
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        $status =  isset($request->status) ? $request->status : "Approved";
        $employee_wfhs = [];
        if(isset($request->from) && isset($request->to)){
            $employee_wfhs = EmployeeWfh::with('user','employee')
                                        ->whereDate('date_from','>=',$from)
                                        ->whereDate('date_from','<=',$to)
                                        ->whereHas('employee',function($q) use($company){
                                            $q->where('company_id',$company);
                                        })
                                        ->where('status',$status)
                                        ->get();
        }
        

        return view('reports.wfh_report', array(
            'header' => 'reports',
            'company'=>$company,
            'from'=>$from,
            'to'=>$to,
            'status'=>$status,
            'employee_wfhs' => $employee_wfhs,
            'companies' => $companies
        ));
    }

    public function export(Request $request){
        $company = isset($request->company) ? $request->company : "";
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        $company_detail = Company::where('id',$company)->first();
        return Excel::download(new EmployeeWfhExport($company,$from,$to), 'Work From Home ' . $company_detail->company_code . ' ' . $from . ' to ' . $to . '.xlsx');
    }

}
