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

                    $payroll_register = PayrollRegister::where('payroll_period_id',$payroll_period->id)
                                                            ->where('user_id',$employee->user_id)
                                                            ->first();

                    if(empty($payroll_register)){
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
                        $basic_pay = $rate / 2; //Basic Pay Computation
                        $lates = 0;
                        $under_time = 0;
                        $salary_adjustment = getUserSalaryAdjustmentAmount($employee->user_id,$payroll_period->id,$payroll_period->payroll_cutoff);
                        $sss_reg_ee = 0;
                        $sss_mpf_ee = 0;
                        $phic_ee = 0;
                        $hdmf_ee = 0;
                        $salary_deduction_taxable = 0;
                        $absences_amount = 0;
                        $lates_amount = 0;
                        $undertime_amount = 0;
                        $ot_amount = 0;
                        $meal_allowances = 0;
                        $salary_allowances = 0;
                        $out_allowances = 0;
                        $incentives_allowances = 0;
                        $discretionary_allowances = 0;
                        $transpo_allowances = 0;
                        $$load_allowances = 0;

                        $payroll_register->monthly_basic_pay = $rate;
                        $payroll_register->daily_rate = ((($rate*12)/313)/8)*9.5; //Daily Rate Computation
                        $payroll_register->basic_pay = $basic_pay;

                        // Allowances
                        $payroll_register->meal_allowance = getUserAllowanceAmount($employee->user_id,3);
                        $payroll_register->salary_allowance = getUserAllowanceAmount($employee->user_id,4);
                        $payroll_register->out_of_town_allowance = getUserAllowanceAmount($employee->user_id,2);
                        $payroll_register->incentives_allowance = getUserAllowanceAmount($employee->user_id,5);
                        $payroll_register->relocation_allowance = getUserAllowanceAmount($employee->user_id,6);
                        $payroll_register->discretionary_allowance = getUserAllowanceAmount($employee->user_id,7);
                        $payroll_register->transport_allowance = getUserAllowanceAmount($employee->user_id,8);
                        $payroll_register->load_allowance = getUserAllowanceAmount($employee->user_id,9);
                        //Salary Adjustment
                        $payroll_register->salary_adjustment = $salary_adjustment;
                        //Witholding tax
                        $payroll_register->withholding_tax = getUserWitholdingTaxAmount($employee->user_id,$basic_pay,$lates,$under_time,$salary_adjustment,$ot_amount,
                            $sss_reg_ee,$sss_mpf_ee,$phic_ee,$hdmf_ee,$salary_deduction_taxable);
                        //Gross Pay
                        $payroll_register->grosspay = getUserGrossPayAmount($basic_pay,$absences_amount,$lates_amount,$undertime_amount,$salary_adjustment,$ot_amount,
                            $meal_allowances,$salary_allowances,$out_allowances,$incentives_allowances,$discretionary_allowances,$transpo_allowances,$load_allowances);
                        //Total Taxable
                        $payroll_register->total_taxable = getUserTotalTaxableAmount($basic_pay,$absences_amount,$lates_amount,$undertime_amount,$salary_adjustment,$ot_amount,
                            $sss_reg_ee,$sss_mpf_ee,$phic_ee,$hdmf_ee,$salary_deduction_taxable);
                        
                        $payroll_register->save();
                        $count++;
                    }
                }
            }
        }

        Alert::success('Successfully Generated (' . $count. ')')->persistent('Dismiss');
        return redirect('/pay-reg?payroll_period=' . $request->payroll_period . '&company=' .$request->company );

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
