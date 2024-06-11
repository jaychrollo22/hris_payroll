<?php

namespace App\Http\Controllers;

use App\Company;
use App\Employee;
use App\PersonnelEmployee;
use App\EmployeeCompany;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeCompanyController extends Controller
{
    //
    public function index()
    {
        // $employeeCompanies = EmployeeCompany::all();
        $companies = Company::with('employee_company')->get();
        $employees = PersonnelEmployee::get();
        return view('employee_companies.index', array(
            'header' => 'masterfiles',
            'companies' => $companies,
            'employees' => $employees,
            // 'employeeCompanies' => $employeeCompanies,
        ));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        // $empComp = EmployeeCompany::where('emp_code', $request->emp_code)->get();
        // dd($empComp->toArray());
        foreach ($request->emp_code as $emp_code) {
            $empComp = EmployeeCompany::where('emp_code', $request->emp_code)->where('company_id', $request->company)->first();
            if ($empComp == null) {
                $newEmpGroup = new EmployeeCompany();
                $newEmpGroup->emp_code = $emp_code;
                $newEmpGroup->company_id = $request->company;
                $newEmpGroup->save();
                Alert::success('Successfully Stored')->persistent('Dismiss');
            }
        }

        return back();
    }
}
