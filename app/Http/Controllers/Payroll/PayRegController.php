<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\PayrollPeriod;
use App\PayrollRegister;
use App\Employee;
use App\Company;
use App\Department;

use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;


class PayRegController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);

        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        $search = isset($request->search) ? $request->search : "";
        $status = isset($request->status) ? $request->status : "Active";
        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";
        $payroll_period = isset($request->payroll_period) ? $request->payroll_period : "";

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        
        $departments = [];
        
        $payroll_registers = PayrollRegister::where('payroll_period_id',$payroll_period);
        
        if($company){
            $department_companies = Employee::when($company,function($q) use($company){
                            $q->where('company_id',$company);
                        })
                        ->groupBy('department_id')
                        ->pluck('department_id')
                        ->toArray();

            $departments = Department::whereIn('id',$department_companies)->where('status','1')
                    ->orderBy('name')
                    ->get();

            $payroll_registers->whereIn('user_id',$department_companies)
                                ->whereIn('user_id',$allowed_companies);

        }else{
            $departments = Department::where('status','1')->orderBy('name')->get();
        }

        $payroll_periods = PayrollPeriod::all();

        $payroll_registers = $payroll_registers->get();
        
        return view(
            'pay_reg.index',
            array(
                'header' => 'pay_reg',
                'payroll_periods' => $payroll_periods,
                'payroll_period' => $payroll_period,
                'companies' => $companies,
                'company' => $company,
                'departments' => $departments,
                'department' => $department,
                'search' => $search,
                'payroll_registers' => $payroll_registers,

            )
        );


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        $payroll_period = PayrollPeriod::where('id',$request->payroll_period)->first();
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        
        $employees = Employee::with('company','department')
                                        ->whereIn('company_id',$allowed_companies)
                                        ->where('company_id',$request->company)
                                        ->where('status','Active')
                                        ->where('id','1') // My Id
                                        ->get();
        $count = 0;
        if($employees && $payroll_period){ 
            if($employees){
                foreach($employees as $employee){

                    $validate_payroll_register = PayrollRegister::where('payroll_period_id',$payroll_period->id)->first();

                    if(empty($validate_payroll_register)){

                        $payroll_register = new PayrollRegister;
                        $payroll_register->payroll_period_id = $payroll_period->id;
                        $payroll_register->user_id = $employee->user_id;
                        $payroll_register->bank_account = $employee->bank_account_number;
                        $payroll_register->name = $employee->first_name . ' ' . $employee->last_name;
                        $payroll_register->position = $employee->position;
                        $payroll_register->employment_status = $employee->status;
                        $payroll_register->company = $employee->company->company_name ?? null;
                        $payroll_register->department = $employee->department->name ?? null;
                        $payroll_register->project = $employee->project;
                        $payroll_register->date_hired = $employee->original_date_hired;
                        $payroll_register->cut_from = $payroll_period->start_date;
                        $payroll_register->cut_to = $payroll_period->end_date;

                        //IF Monthly
                        $rate = $employee->rate ? Crypt::decryptString($employee->rate) : "";
                        $payroll_register->monthly_basic_pay = $rate;
                        $payroll_register->daily_rate = ((($rate*12)/313)/8)*9.5; //Daily Rate Computation
                        $payroll_register->basic_pay = $rate / 2; //Basic Pay Computation

                        $payroll_register->save();
                        $count++;
                    }
                }
            }
        }

        Alert::success('Successfully Generated (' . $count. ')')->persistent('Dismiss');
        return redirect('/pay-reg');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
