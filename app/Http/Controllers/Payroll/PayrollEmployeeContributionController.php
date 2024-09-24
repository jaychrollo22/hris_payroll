<?php


namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\PayrollEmployeeContribution;
use App\Employee;

use RealRashid\SweetAlert\Facades\Alert;

class PayrollEmployeeContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $contributions = PayrollEmployeeContribution::all();
        $employees = Employee::select('user_id','first_name','last_name')->where('status','Active')->get();

        return view(
            'payroll_employee_contributions.index',
            array(
                'header' => 'contributions',
                'contributions' => $contributions,
                'employees' => $employees,

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
        $contribution = new PayrollEmployeeContribution;
        $contribution->user_id = $request->input('user_id');
        $contribution->sss_reg_ee = $request->input('sss_reg_ee');
        $contribution->sss_mpf_ee = $request->input('sss_mpf_ee');
        $contribution->phic_ee = $request->input('phic_ee');
        $contribution->hdmf_ee = $request->input('hdmf_ee');
        $contribution->sss_reg_er = $request->input('sss_reg_er');
        $contribution->sss_mpf_er = $request->input('sss_mpf_er');
        $contribution->sss_ec = $request->input('sss_ec');
        $contribution->phic_er = $request->input('phic_er');
        $contribution->hdmf_er = $request->input('hdmf_er');
        $contribution->payment_schedule = $request->input('payment_schedule');
        $contribution->save();

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
        $payroll = PayrollEmployeeContribution::findOrFail($id);
        $payroll->sss_reg_ee = $request->input('sss_reg_ee');
        $payroll->sss_mpf_ee = $request->input('sss_mpf_ee');
        $payroll->phic_ee = $request->input('phic_ee');
        $payroll->hdmf_ee = $request->input('hdmf_ee');
        $payroll->sss_reg_er = $request->input('sss_reg_er');
        $payroll->sss_mpf_er = $request->input('sss_mpf_er');
        $payroll->sss_ec = $request->input('sss_ec');
        $payroll->phic_er = $request->input('phic_er');
        $payroll->hdmf_er = $request->input('hdmf_er');
        $payroll->payment_schedule = $request->input('payment_schedule');
        $payroll->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
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
        //
    }
}
