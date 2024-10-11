<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Company;
use App\PayrollPeriod;
use App\PayrollOvertimeAdjustment;
use RealRashid\SweetAlert\Facades\Alert;
use Excel;
use App\Exports\PayrollOvertimeAdjustmentExport;
use App\Imports\PayrollOvertimeAdjustmentImport;

class PayrollOvertimeAdjustmentController extends Controller
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
            $payroll_period = isset($request->payroll_period) ? $request->payroll_period : "";

            $employees = Employee::select('id','user_id','first_name','last_name','middle_name')
                ->whereIn('company_id',$allowed_companies)
                ->where('status','Active')
                ->get();

            // Fetch salary adjustments
            $overtime_adjustments = PayrollOvertimeAdjustment::where('status',$status)
                ->whereHas('employee',function($q) use($allowed_companies){
                    $q->whereIn('company_id',$allowed_companies);
                })
                ->with('employee.company');

            if($company){
                $overtime_adjustments = $overtime_adjustments->whereHas('employee',function($q) use($company){
                    $q->where('company_id',$company);
                });
            }

            if($payroll_period) $overtime_adjustments = $overtime_adjustments->where('payroll_period_id',$payroll_period);
            
            $overtime_adjustments = $overtime_adjustments->get();

            return view('overtime_adjustments.index', array(
                'header' => 'overtime_adjustments',
                'overtime_adjustments' => $overtime_adjustments,
                'payroll_periods' => PayrollPeriod::all(),
                'payroll_period' => $payroll_period,
                'payroll_cutoff' => '',
                'employees' => $employees,
                'companies' => $companies,
                'company' => $company,
                'status' => $status
            ));
        }
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
            'payroll_period' => 'required',
            'amount' => 'required',
            'reason' => 'required'
        ]);

        $adjustment = new PayrollOvertimeAdjustment;
        $adjustment->user_id = $request->employee;
        $adjustment->payroll_period_id = $request->payroll_period;
        // $adjustment->payroll_cutoff = $request->payroll_cutoff;
        $adjustment->amount = $request->amount;
        $adjustment->type = $request->type;
        $adjustment->status = "Active";
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
        $adjustment = PayrollOvertimeAdjustment::findOrfail($id);
        $adjustment->user_id = $request->employee;
        $adjustment->payroll_period_id = $request->payroll_period;
        // $adjustment->payroll_cutoff = $request->payroll_cutoff;
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
        PayrollOvertimeAdjustment::find($id)->delete();
        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }

    /**
     * Export to excel
     *
     */
    public function export(Request $request){
        $company = isset($request->company) ? $request->company : "";
        $status = isset($request->status) ? $request->status : "";
        $payroll_period = isset($request->payroll_period) ? $request->payroll_period : "";
        $company_detail = Company::where('id',$company)->first();

        $company_code = $company_detail ? $company_detail->company_code : "";

        return Excel::download(new PayrollOvertimeAdjustmentExport($company,$status,$payroll_period), $company_code. ' Payroll Overtime Adjustment Export.xlsx');
    }


    
    /**
     * Import for excel for mass upload
     *
     */
    public function import(Request $request)
    {
        ini_set('memory_limit', '-1');
        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray(new PayrollOvertimeAdjustmentImport, $request->file('file'));

        if(count($data[0]) > 0){
            $save_count = 0;
            $not_save = [];
            foreach($data[0] as $key => $value){
                $overtime_adjustment = PayrollOvertimeAdjustment::where('user_id',$value['user_id'])
                    ->where('payroll_period_id',$value['payroll_period_id'])
                    ->where('payroll_cutoff',$value['payroll_cutoff'])
                    ->where('type',$value['type'])
                    ->first();

                if($overtime_adjustment){
                    if(isset($value['payroll_period_id'])) $overtime_adjustment->payroll_period_id = $value['payroll_period_id'];
                    if(isset($value['payroll_cutoff'])) $overtime_adjustment->payroll_cutoff = $value['payroll_cutoff'];
                    if(isset($value['amount'])) $overtime_adjustment->amount = $value['amount'];
                    if(isset($value['type'])) $overtime_adjustment->type = $value['type'];
                    if(isset($value['status'])) $overtime_adjustment->status = $value['status'];
                    if(isset($value['reason'])) $overtime_adjustment->reason = $value['reason'];
                
                    $overtime_adjustment->save();
                    $save_count+=1;
                }else{
                    $overtime_adjustment = new PayrollOvertimeAdjustment;
                    $overtime_adjustment->user_id = $value['user_id'];
                    $overtime_adjustment->payroll_period_id = $value['payroll_period_id'];
                    $overtime_adjustment->payroll_cutoff = $value['payroll_cutoff'];
                    $overtime_adjustment->amount = $value['amount'];
                    $overtime_adjustment->type = $value['type'];
                    $overtime_adjustment->status = $value['status'];
                    $overtime_adjustment->reason = $value['reason'];
                    $overtime_adjustment->save();
                    $save_count+=1;
                }                                         
            }

            Alert::success('Successfully Import Payroll Overtime Adjustments (' . $save_count. ')')->persistent('Dismiss');
            return redirect('payroll-overtime-adjustments');
        }
    }
}
