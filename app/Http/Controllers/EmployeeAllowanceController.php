<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Allowance;
use App\Company;
use App\EmployeeAllowance;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use App\Exports\EmployeeAllowanceExport;
use Excel;

class EmployeeAllowanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(checkUserPrivilege('masterfiles_employee_allowances',auth()->user()->id) == 'yes'){

            $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

            $companies = Company::whereHas('employee_has_company')
                                    ->whereIn('id',$allowed_companies)
                                    ->get();

            $company = isset($request->company) ? $request->company : "";
            $status = isset($request->status) ? $request->status : "Active";

            $employees = Employee::select('id','user_id','first_name','last_name','middle_name')
                                        ->whereIn('company_id',$allowed_companies)
                                        ->where('status','Active')
                                        ->get();

            $employeeAllowances = EmployeeAllowance::whereHas('employee',function($q) use($company){
                                                        $q->where('company_id',$company);
                                                    })
                                                    ->where('status',$status)
                                                    ->get();

            $allowanceTypes = Allowance::all();

            return view('employee_allowances.employee_allowance', array(
                'header' => 'masterfiles',
                'employeeAllowances' => $employeeAllowances,
                'allowanceTypes' => $allowanceTypes,
                'employees' => $employees,
                'companies' => $companies,
                'company' => $company,
                'status' => $status,

            ));
               
        }else{
            return 'Not Allowed. Please contact administrator. Thank you';
        }   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $this->validate($request, [
            'allowance_type' => 'required',
            'user_id' => 'required',
            'amount' => 'required', 'min:1',
        ]);

        $employeeAllowances = new EmployeeAllowance;
        $employeeAllowances->allowance_id = $request->allowance_type;
        $employeeAllowances->user_id = $request->user_id;
        $employeeAllowances->description = $request->description;
        $employeeAllowances->application = $request->application;
        $employeeAllowances->type = $request->type;
        $employeeAllowances->schedule = $request->schedule;
        $employeeAllowances->allowance_amount = $request->amount;
        $employeeAllowances->end_date = $request->end_date;
        $employeeAllowances->status = 'Active';
        $employeeAllowances->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }
    
    public function update(Request $request, $id)
    {
        // Validation
        $this->validate($request, [
            'amount' => 'required', 'min:1',
        ]);

        $employeeAllowances = EmployeeAllowance::findOrFail($id);
        $employeeAllowances->allowance_amount = $request->amount;
        $employeeAllowances->description = $request->description;
        $employeeAllowances->application = $request->application;
        $employeeAllowances->type = $request->type;
        $employeeAllowances->schedule = $request->schedule;
        $employeeAllowances->allowance_amount = $request->amount;
        $employeeAllowances->end_date = $request->end_date;
        $employeeAllowances->status = 'Active';
        $employeeAllowances->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return redirect('edit-employee-allowance/' . $id);
    }

    public function disable($id)
    {
        EmployeeAllowance::Where('id', $id)->update(['status' => 'Inactive']);
        Alert::success('Employee Allowance Inactive')->persistent('Dismiss');
        return back();
    }

    public function delete($id)
    {
        EmployeeAllowance::Where('id', $id)->delete();
        Alert::success('Employee Allowance has been deleted.')->persistent('Dismiss');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmployeeAllowance  $employeeAllowance
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $allowanceTypes = Allowance::all();

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $employee_allowance = EmployeeAllowance::with('employee')
                                                    ->whereHas('employee',function($q) use($allowed_companies){
                                                        $q->whereIn('company_id',$allowed_companies);
                                                    })
                                                    ->where('id',$id)
                                                    ->first();
        $employees = Employee::select('id','user_id','first_name','last_name','middle_name')
                                    ->whereIn('company_id',$allowed_companies)
                                    ->where('status','Active')
                                    ->get(); 

        if($employee_allowance){
            return view('employee_allowances.edit_emp_allowance', array(
                'header' => 'masterfiles',
                'allowanceTypes' => $allowanceTypes,
                'employee_allowance' => $employee_allowance,
                'employees' => $employees
            ));
        }else{
            return 'You are not allowed to proceed. Thank you.';
        }
        
    }


    public function export(Request $request){
        $company = isset($request->company) ? $request->company : "";
        $status = isset($request->status) ? $request->status : "";
        $company_detail = Company::where('id',$company)->first();
        return Excel::download(new EmployeeAllowanceExport($company,$status), $company_detail->company_code. ' Employee Allowances Export.xlsx');
    }

}
