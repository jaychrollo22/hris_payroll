<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\PayrollPeriod;

use RealRashid\SweetAlert\Facades\Alert;

class PayrollPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payroll_periods = PayrollPeriod::all();

        return view(
            'payroll_periods.index',
            array(
                'header' => 'payroll_periods',
                'payroll_periods' => $payroll_periods,

            )
        );
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

        // Create a new payroll period
        $payrollPeriod = new PayrollPeriod;
        $payrollPeriod->payroll_name = $request->input('payroll_name');
        $payrollPeriod->start_date = $request->input('start_date');
        $payrollPeriod->end_date = $request->input('end_date');
        $payrollPeriod->payroll_frequency = $request->input('payroll_frequency');
        $payrollPeriod->cut_off_date = $request->input('cut_off_date');
        $payrollPeriod->payment_date = $request->input('payment_date');
        $payrollPeriod->total_days = $request->input('total_days');
        $payrollPeriod->status = $request->input('status');
        $payrollPeriod->notes = $request->input('notes', null);

        // Save the payroll period to the database
        $payrollPeriod->save();

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
        // Find the payroll period by its ID
        $payrollPeriod = PayrollPeriod::findOrFail($id);

        // Update the payroll period with the new data
        $payrollPeriod->payroll_name = $request->input('payroll_name');
        $payrollPeriod->start_date = $request->input('start_date');
        $payrollPeriod->end_date = $request->input('end_date');
        $payrollPeriod->payroll_frequency = $request->input('payroll_frequency');
        $payrollPeriod->cut_off_date = $request->input('cut_off_date');
        $payrollPeriod->payment_date = $request->input('payment_date');
        $payrollPeriod->total_days = $request->input('total_days');
        $payrollPeriod->status = $request->input('status');
        $payrollPeriod->notes = $request->input('notes', null);

        // Save the changes to the database
        $payrollPeriod->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
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
        PayrollPeriod::where('id',$id)->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }
}
