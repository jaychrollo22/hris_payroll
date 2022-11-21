<?php

namespace App\Http\Controllers;

use App\Company;
use App\Employee;
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
        $employees = Employee::all();
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
        foreach ($request->emp_code as $emp_code) {
            $newEmpGroup = new EmployeeCompany();
            $newEmpGroup->emp_code = $emp_code;
            $newEmpGroup->company_id = $request->company;
            $newEmpGroup->save();
        }
        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }
}
