<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Leave;
use App\EmployeeLeave;
use App\Company;
use App\Department;

class LeaveBalancesController extends Controller
{
    public function index(Request $request){
        
       

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        $allowed_locations = getUserAllowedLocations(auth()->user()->id);
        $allowed_projects = getUserAllowedProjects(auth()->user()->id);

        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";

        $departments = [];
        if($company){
            $department_companies = Employee::select('department_id')->when($company,function($q) use($company){
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
        $employees = [];
        if($company){

            $employees = Employee::select('id','user_id','employee_number','first_name','last_name','department_id','company_id','status','original_date_hired')->with('department','company','employee_leave_credits.leave')
                                    ->whereIn('company_id',$allowed_companies)
                                    ->where('status','Active')
                                    ->whereHas('employee_leave_credits')
                                    ->when($company,function($q) use($company){
                                        $q->where('company_id',$company);
                                    })
                                    ->when($department,function($q) use($department){
                                        $q->where('department_id',$department);
                                    })
                                    ->when($allowed_locations,function($q) use($allowed_locations){
                                        $q->whereIn('location',$allowed_locations);
                                    })
                                    ->when($allowed_projects,function($q) use($allowed_projects){
                                        $q->whereIn('project',$allowed_projects);
                                    })
                                    ->orderBy('first_name','ASC')
                                    ->get();

        }                       
        return view('employee_leave_balances.index', array(
            'header' => 'masterfiles',
            'employees' => $employees,
            'companies' => $companies,
            'departments' => $departments,
            'company' => $company,
            'department' => $department,
        ));

    }
}
