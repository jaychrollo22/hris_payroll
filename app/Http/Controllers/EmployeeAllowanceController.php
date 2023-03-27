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
        $employeeAllowances = EmployeeAllowance::all();
        $allowanceTypes = Allowance::all();
        
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $employees = Employee::whereIn('company_id',$allowed_companies)->get();

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
            'employee' => 'required',
            'amount' => 'required', 'min:1',
        ]);

        $employeeAllowances = new EmployeeAllowance;
        $employeeAllowances->allowance_id = $request->allowance_type;
        $employeeAllowances->employee_id = $request->employee;
        $employeeAllowances->allowance_amount = $request->amount;
        $employeeAllowances->schedule = $request->schedule;
        $employeeAllowances->status = 'Active';
        $employeeAllowances->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }
    public function disable($id)
    {
        EmployeeAllowance::Where('id', $id)->update(['status' => 'Inactive']);
        Alert::success('Employee Allowance Inactive')->persistent('Dismiss');
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
    public function edit(EmployeeAllowance $employeeAllowance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmployeeAllowance  $employeeAllowance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeAllowance $employeeAllowance)
    {
        //
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
