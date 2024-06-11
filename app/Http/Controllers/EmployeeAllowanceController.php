<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Allowance;
use App\EmployeeAllowance;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeAllowanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $allowanceTypes = Allowance::all();
        
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $employees = Employee::select('id','user_id','first_name','last_name','middle_name')
                                    ->whereIn('company_id',$allowed_companies)
                                    ->where('status','Active')
                                    ->get();

        $employeeAllowances = EmployeeAllowance::whereHas('employee',function($q) use($allowed_companies){
                                                    $q->whereIn('company_id',$allowed_companies);
                                                })
                                                ->get();

        return view('employee_allowances.employee_allowance', array(
            'header' => 'masterfiles',
            'employeeAllowances' => $employeeAllowances,
            'allowanceTypes' => $allowanceTypes,
            'employees' => $employees,

        ));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $employeeAllowances->allowance_amount = $request->amount;
        $employeeAllowances->schedule = $request->schedule;
        $employeeAllowances->status = 'Active';
        $employeeAllowances->save();

        Alert::success('Successfully Stored')->persistent('Dismiss');
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
        $employeeAllowances->schedule = $request->schedule;
        $employeeAllowances->status = 'Active';
        $employeeAllowances->save();

        Alert::success('Successfully Stored')->persistent('Dismiss');
        return redirect('/employee-allowance');
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
     * Display the specified resource.
     *
     * @param  \App\EmployeeAllowance  $employeeAllowance
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeAllowance $employeeAllowance)
    {
        //
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


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmployeeAllowance  $employeeAllowance
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeAllowance $employeeAllowance)
    {
        //
    }
}
