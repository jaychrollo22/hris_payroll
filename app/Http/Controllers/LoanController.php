<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Loan;
use App\LoanType;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $loans = Loan::all();
        return view('loans.loan_index', array(
            'header' => 'Loans',
            'loans' => $loans,
        ));
    }
    public function loan_reg()
    {
        $loans = Loan::with('loan_type', 'employee')->get();
        $loanTypes = LoanType::all();
        $employees = Employee::all();

        return view('loans.loan_register', array(
            'header' => 'Payroll',
            'loans' => $loans,
            'loanTypes' => $loanTypes,
            'employees' => $employees,
        ));
    }

    public function store_loanReg(Request $request)
    {
        // Validation
        $this->validate($request, [
            'loan_type' => 'required',
            'employee' => 'required',
            'amount' => 'required', 'min:1',
            'monthly_ammort_amt' => 'required', 'min:1',
            'start_date' => 'required',
            'expiry_date' => 'required|date|after:start_date',
            'initial_amount' => 'required', 'min:1',
        ]);

        $loans = new Loan;
        $loans->loan_type_id = $request->loan_type;
        $loans->employee_id = $request->employee;
        $loans->amount = $request->amount;
        $loans->monthly_ammort_amt = $request->monthly_ammort_amt;
        $loans->initial_amount = $request->initial_amount;
        $loans->start_date = $request->start_date;
        $loans->expiry_date = $request->expiry_date;
        $loans->save();

        Alert::success('Successfully Stored')->persistent('Dismiss');
        return back();
    }
    public function loan_report()
    {
        return view('reports.loan_report', array(
            'header' => 'reports',
        ));
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show(Loan $loan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan $loan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        //
    }
}
