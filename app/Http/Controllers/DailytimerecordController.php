<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\EmployeeDtr;
use App\Company;

use App\Exports\EmployeeDtrExport;
use Excel;

class DailytimerecordController extends Controller
{
    //

    public function dtr ()
    {
        return view('forms.dtr.dtr',
        array(
            'header' => 'forms',
        )); 
    }

    public function dtr_report(Request $request){
        
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        $company = isset($request->company) ? $request->company : "";
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        $status =  isset($request->status) ? $request->status : "Approved";
        $employee_dtrs = [];
        if(isset($request->from) && isset($request->to)){
            $employee_dtrs = EmployeeDtr::with('user','employee')
                                        ->whereDate('dtr_date','>=',$from)
                                        ->whereDate('dtr_date','<=',$to)
                                        ->whereHas('employee',function($q) use($company){
                                            $q->where('company_id',$company);
                                        })
                                        ->where('status',$status)
                                        ->get();
        }
        

        return view('reports.dtr_report', array(
            'header' => 'reports',
            'company'=>$company,
            'from'=>$from,
            'to'=>$to,
            'status'=>$status,
            'employee_dtrs' => $employee_dtrs,
            'companies' => $companies
        ));
    }

    public function export(Request $request){
        $company = isset($request->company) ? $request->company : "";
        $from = isset($request->from) ? $request->from : "";
        $to =  isset($request->to) ? $request->to : "";
        $company_detail = Company::where('id',$company)->first();
        return Excel::download(new EmployeeDtrExport($company,$from,$to), $company_detail->company_code . ' ' . $from . ' to ' . $to . ' DTR Export.xlsx');
    }


}
