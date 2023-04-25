<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employee;
use App\Company;
use App\EmployeeOb;
use App\Exports\EmployeeObExport;
use Excel;

class OfficialbusinessController extends Controller
{
    //
    public function officialbusiness()
    {
        return view('forms.officialbusiness.officialbusiness',
        array(
            'header' => 'forms',
        ));
    }

    public function ob_report(Request $request)
    {   
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        $company = isset($request->company) ? $request->company : "";
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        $status =  isset($request->status) ? $request->status : "Approved";
        $employee_obs = [];
        if(isset($request->from) && isset($request->to)){
            $employee_obs = EmployeeOb::with('user','employee')
                                        ->whereDate('applied_date','>=',$from)
                                        ->whereDate('applied_date','<=',$to)
                                        ->whereHas('employee',function($q) use($company){
                                            $q->where('company_id',$company);
                                        })
                                        ->where('status',$status)
                                        ->get();
        }
        

        return view('reports.ob_report', array(
            'header' => 'reports',
            'company'=>$company,
            'from'=>$from,
            'to'=>$to,
            'status'=>$status,
            'employee_obs' => $employee_obs,
            'companies' => $companies
        ));
    }

    public function export(Request $request){
        $company = isset($request->company) ? $request->company : "";
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        $company_detail = Company::where('id',$company)->first();
        return Excel::download(new EmployeeObExport($company,$from,$to), 'Official Business ' . $company_detail->company_code . ' ' . $from . ' to ' . $to . '.xlsx');
    }
}
