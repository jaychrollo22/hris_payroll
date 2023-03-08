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
        $companies = Company::whereHas('employee_has_company')->get();

        $company = isset($request->company) ? $request->company : "";
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        $employee_obs = [];
        if(isset($request->from) && isset($request->to)){
            $employee_obs = EmployeeOb::with('user','employee')
                                        ->whereDate('approved_date','>=',$from)
                                        ->whereDate('approved_date','<=',$to)
                                        ->whereHas('employee',function($q) use($company){
                                            $q->where('company_id',$company);
                                        })
                                        ->where('status','Approved')
                                        ->get();
        }
        

        return view('reports.ob_report', array(
            'header' => 'reports',
            'company'=>$company,
            'from'=>$from,
            'to'=>$to,
            'employee_obs' => $employee_obs,
            'companies' => $companies
        ));
    }

    public function export(Request $request){
        $company = isset($request->company) ? $request->company : "";
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        $company_detail = Company::where('id',$company)->first();
        return Excel::download(new EmployeeObExport($company,$from,$to), $company_detail->company_code . ' ' . $from . ' to ' . $to . ' OB Export.xlsx');
    }
}
