<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Incentive;
use App\EmployeeIncentive;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeIncentiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employeeIncentives = EmployeeIncentive::all();
        $incentiveTypes = Incentive::all();
        $employees = Employee::all();
        return view('employee_incentives.employee_incentive', array(
            'header' => 'masterfiles',
            'employeeIncentives' => $employeeIncentives,
            'incentiveTypes' => $incentiveTypes,
            'employees' => $employees,
        ));
    }

    public function store(Request $request)
    {
        // Validation
        $this->validate($request, [
            'incentive_type' => 'required',
            'employee' => 'required',
            'amount' => 'required', 'min:1',
        ]);

        $employeeAllowances = new EmployeeIncentive;
        $employeeAllowances->incentive_id = $request->incentive_type;
        $employeeAllowances->employee_id = $request->employee;
        $employeeAllowances->incentive_amount = $request->amount;
        $employeeAllowances->status = 'Active';
        $employeeAllowances->save();

        Alert::success('Successfully Stored')->persistent('Dismiss');
        return back();
    }
    public function disable($id)
    {
        EmployeeIncentive::Where('id', $id)->update(['status' => 'Inactive']);
        Alert::success('Employee Incentive Inactive')->persistent('Dismiss');
        return back();
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\EmployeeIncentive  $employeeIncentive
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeIncentive $employeeIncentive)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmployeeIncentive  $employeeIncentive
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeIncentive $employeeIncentive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmployeeIncentive  $employeeIncentive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeIncentive $employeeIncentive)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmployeeIncentive  $employeeIncentive
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeIncentive $employeeIncentive)
    {
        //
    }
}
