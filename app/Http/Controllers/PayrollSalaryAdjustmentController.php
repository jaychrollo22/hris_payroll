<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Company;
use App\PayrollSalaryAdjustment;
use RealRashid\SweetAlert\Facades\Alert;
use Excel;
use App\Imports\PayrollSalaryAdjustmentImport;

class PayrollSalaryAdjustmentController extends Controller
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

            // Fetch salary adjustments
            $salary_adjustments = PayrollSalaryAdjustment::where('status',$status)
                ->whereHas('employee',function($q) use($allowed_companies){
                    $q->whereIn('company_id',$allowed_companies);
                })
                ->with('employee.company');

            if($company){
                $salary_adjustments = $salary_adjustments->whereHas('employee',function($q) use($company){
                    $q->where('company_id',$company);
                });
            }
            
            $salary_adjustments = $salary_adjustments->get();

            return view('salary_adjustments.index', array(
                'header' => 'salary_adjustments',
                'salary_adjustments' => $salary_adjustments,
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
            'effectivity_date' => 'required',
            'amount' => 'required',
            'reason' => 'required'
        ]);

        $adjustment = new PayrollSalaryAdjustment;
        $adjustment->user_id = $request->employee;
        $adjustment->effectivity_date = $request->effectivity_date;
        $adjustment->amount = $request->amount;
        $adjustment->type = $request->type;
        $user->status = "Active";
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

    /**
     * Import for excel for mass upload
     *
     */
    public function import(Request $request)
    {
        ini_set('memory_limit', '-1');
        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray(new PayrollSalaryAdjustmentImport, $request->file('file'));

        if(count($data[0]) > 0){
            $save_count = 0;
            $not_save = [];
            foreach($data[0] as $key => $value){
                $salary_adjustment = PayrollSalaryAdjustment::where('user_id',$value['user_id'])
                    ->first();
                if($salary_adjustment){
                    // if(isset($value['effectivity_date'])) $salary_adjustment->effectivity_date = $value['effectivity_date'];
                    if(isset($value['effectivity_date'])) $salary_adjustment->effectivity_date = '2024-09-23';
                    if(isset($value['amount'])) $salary_adjustment->amount = $value['amount'];
                    if(isset($value['type'])) $salary_adjustment->type = $value['type'];
                    if(isset($value['status'])) $salary_adjustment->status = $value['status'];
                    if(isset($value['reason'])) $salary_adjustment->reason = $value['reason'];
                
                    $salary_adjustment->save();
                    $save_count+=1;
                }else{
                    $salary_adjustment = new PayrollSalaryAdjustment;
                    $salary_adjustment->user_id = $value['user_id'];
                    // $salary_adjustment->effectivity_date = $value['effectivity_date'];
                    $salary_adjustment->effectivity_date = '2024-09-23';
                    $salary_adjustment->amount = $value['amount'];
                    $salary_adjustment->type = $value['type'];
                    $salary_adjustment->status = $value['status'];
                    $salary_adjustment->reason = $value['reason'];
                    $salary_adjustment->save();
                    $save_count+=1;
                }                                         
            }

            Alert::success('Successfully Import Payroll Salary Adjustments (' . $save_count. ')')->persistent('Dismiss');
            return redirect('payroll-salary-adjusments');
        }
    }
}
