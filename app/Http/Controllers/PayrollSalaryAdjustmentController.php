<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\PayrollSalaryAdjustment;
use RealRashid\SweetAlert\Facades\Alert;

class PayrollSalaryAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch salary adjustments
        $salary_adjustments = PayrollSalaryAdjustment::with('user')->get();
        $employees = Employee::with('user_info')->get();
        return view('salary_adjustments.index', array(
            'header' => 'salary_adjustments',
            'salary_adjustments' => $salary_adjustments,
            'employees' => $employees
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
        $request->validate([
            'employee' => 'required',
            'effectivity_date' => 'required',
            'amount' => 'required',
            'reason' => 'required'
        ]);

        $adjustment = new PayrollSalaryAdjustment;
        $adjustment->user_id = $request->employee;
        $adjustment->effectivity_date = $request->effectivity_date;
        $adjustment->amount = $request->amount;
        $adjustment->type = $request->type;
        $adjustment->reason = $request->reason;
        $adjustment->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $adjustment = PayrollSalaryAdjustment::findOrfail($id);
        $adjustment->user_id = $request->employee;
        $adjustment->effectivity_date = $request->effectivity_date;
        $adjustment->amount = $request->amount;
        $adjustment->type = $request->type;
        $adjustment->reason = $request->reason;
        $adjustment->save();

        Alert::success('Successfully updated')->persistent('Dismiss');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PayrollSalaryAdjustment::find($id)->delete();
        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }
}
