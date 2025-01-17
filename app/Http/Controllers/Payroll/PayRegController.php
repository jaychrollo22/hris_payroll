<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\PayrollPeriod;
use App\PayrollRegister;
use App\Employee;
use App\Company;
use App\Department;
use App\PayrollEmployeeContribution;
use App\User;
use App\Level;

use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Excel;
use App\Exports\PayrollRegisterExport;
use Carbon\Carbon;
use App\Imports\PayrollRegisterImport;
use App\Mail\PayslipNotification;
use Illuminate\Support\Facades\Mail;
class PayRegController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $allowed_companies = getUserAllowedPayrollCompanies(auth()->user()->id);
        $allowed_levels = getUserAllowedPayrollLevels(auth()->user()->id);

        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        $levels = Level::whereIn('id',$allowed_levels)->get();

        $search = isset($request->search) ? $request->search : "";
        $status = isset($request->status) ? $request->status : "Active";
        $company = isset($request->company) ? $request->company : "";
        $level = isset($request->level) ? $request->level : "";
        $department = isset($request->department) ? $request->department : "";
        $payroll_period = isset($request->payroll_period) ? $request->payroll_period : "";
        
        $departments = [];
        
        $payroll_registers = PayrollRegister::where('payroll_period_id',$payroll_period);
        
        if($department){
            $payroll_registers->whereHas('employee',function($q) use($department){
                $q->where('department_id',$department);
            });
        }

        if($company){
            $department_companies = Employee::when($company != "All",function($q) use($company){
                            $q->where('company_id',$company);
                        })
                        ->when($company == "All",function($q) use($allowed_companies){
                            $q->whereIn('company_id',$allowed_companies);
                        })
                        ->groupBy('department_id')
                        ->pluck('department_id')
                        ->toArray();

            $departments = Department::whereIn('id',$department_companies)->where('status','1')
                    ->orderBy('name')
                    ->get();
            
            $payroll_registers->whereHas('employee',function($q) use($company,$allowed_companies,$allowed_levels,$search){
                $q->when($company != "All", function($q2) use($company){
                    $q2->where('company_id',$company);
                })
                ->when($company == "All", function($q2) use($allowed_companies){
                    $q2->whereIn('company_id',$allowed_companies);
                })
                ->when($search, function($q2) use($search){
                    $q2->where('first_name', 'like' , '%' .  $search . '%')->orWhere('last_name', 'like' , '%' .  $search . '%')
                        ->orWhere('employee_number', 'like' , '%' .  $search . '%')
                        ->orWhere('user_id', 'like' , '%' .  $search . '%')
                        ->orWhereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", ["%{$search}%"])
                        ->orWhereRaw("CONCAT(`last_name`, ' ', `first_name`) LIKE ?", ["%{$search}%"]);
                })
                ->whereIn('level',$allowed_levels);
            });

        }else{
            $departments = Department::where('status','1')->orderBy('name')->get();
        }

        $payroll_periods = PayrollPeriod::all();
        $payroll_period_detail = PayrollPeriod::where('id',$payroll_period)->first();
        $payroll_registers = $payroll_registers->get();

        return view(
            'pay_reg.index',
            array(
                'header' => 'pay_reg',
                'payroll_periods' => $payroll_periods,
                'payroll_period' => $payroll_period,
                'payroll_period_detail' => $payroll_period_detail,
                'companies' => $companies,
                'level' => $level,
                'levels' => $levels,
                'company' => $company,
                'departments' => $departments,
                'department' => $department,
                'search' => $search,
                'payroll_registers' => $payroll_registers,
                'payreg_for_postings' => $payroll_registers->where('posting_status','Unposted'),
                'payreg_for_unpostings' => $payroll_registers->where('posting_status','Posted'),
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
        $allowed_companies = getUserAllowedPayrollCompanies(auth()->user()->id);
        $allowed_levels = getUserAllowedPayrollLevels(auth()->user()->id);
        
        $employees = Employee::with('company','department')
                                        ->whereIn('company_id',$allowed_companies)
                                        ->where('company_id',$request->company)
                                        ->when($request->department,function($q) use($request){
                                            $q->where('department_id',$request->department);
                                        })
                                        ->where('status','Active')
                                        ->whereIn('level',$allowed_levels)
                                        //->where('id','1') // My Id
                                        ->get();
        $count = 0;
        if($employees && $payroll_period){ 

            if($employees){

                foreach($employees as $employee){
                    $is_executive = $employee->level == 4 ? true : false;
                    $is_consultant = $employee->level == 5 ? true : false;
                    $is_payroll_register_exist = 0;
                    $payroll_register = PayrollRegister::where('payroll_period_id',$payroll_period->id)
                                                            ->where('user_id',$employee->user_id)
                                                            ->first();
                    if(empty($payroll_register)){
                        $payroll_register = new PayrollRegister;
                    }else{
                        $is_payroll_register_exist = 1;
                    }
                    
                    $payroll_register->payroll_period_id = $payroll_period->id;
                    $payroll_register->user_id = $employee->user_id;
                    $payroll_register->bank_account = $employee->bank_account_number;
                    $payroll_register->name = $employee->last_name . ', ' . $employee->first_name;
                    $payroll_register->position = $employee->position;
                    $payroll_register->employment_status = $employee->status;
                    $payroll_register->company =  $employee->company ? $employee->company->company_name : null;
                    $payroll_register->department = $employee->department ? $employee->department->name : null;
                    $payroll_register->project = $employee->project;
                    $payroll_register->date_hired = $employee->original_date_hired;
                    $payroll_register->cut_from = $payroll_period->start_date;
                    $payroll_register->cut_to = $payroll_period->end_date;
                    
                    $no_of_days_worked = getUserNoOfDaysWorked($employee->user_id,$payroll_period->id);
                    $rate = $employee->rate ? Crypt::decryptString($employee->rate) : "";
                    $basic_pay = 0;
                    $daily_rate = 0;
                    $is_monthly = ($employee->work_description == 'Monthly') ? true : false;

                    if($rate){
                        if($is_monthly){
                            $basic_pay = $rate / 2;
                            $daily_rate = ((($rate*12)/313)/8)*9.5;
                        }else{
                            $basic_pay = $rate * $no_of_days_worked;
                            $daily_rate = $rate;
                        }
                    }
                    
                    if($no_of_days_worked > 5 || $is_executive || $is_consultant){
                        $absences_amount = 0;
                        $lates_amount = 0;
                        $undertime_amount = 0;
                        $salary_adjustment = 0;
                        $overtime_amount = 0;

                        if(!$is_executive && !$is_consultant){
                            $lates_amount = getUserLatesAmount($employee->user_id,$payroll_period->id);
                            $undertime_amount = getUserUndertimeAmount($employee->user_id,$payroll_period->id);
                            $salary_adjustment = getUserSalaryAdjustmentAmount($employee->user_id,$payroll_period->id);
                            $overtime_amount = getUserOvertime($employee->user_id,$payroll_period->id);
                        }

                        $accumulated_amount = ($basic_pay-$absences_amount-$lates_amount-$undertime_amount+$salary_adjustment+$overtime_amount);
                        $total_accumulated = $accumulated_amount;
                        $cut_off = $payroll_period->payroll_cutoff;
                        $reg_ee = 0;
                        $mpf_ee = 0;
                        $reg_er = 0;
                        $mpf_er = 0;
                        $ec = 0;
                        $month_15 = $accumulated_amount;
                        $month_30 = 0;
                        $sss_reg_ee = 0;
                        $sss_mpf_ee = 0;
                        $sss_reg_er = 0;
                        $sss_mpf_er = 0;
                        $sss_ec = 0;
                        $phic_ee = 0;
                        $hdmf_ee = 0;
                        $month_15_phic_ee = 0;
                        $month_15_hdmf_ee = 0;

                        //Get first cut off contribution
                        if($cut_off == 'Second Cut-Off'){
                            $payment_date = Carbon::parse($payroll_period->payment_date);
                            $month_30 = $accumulated_amount;
                            //Get previous payroll accumulated amount
                            if($previous_payreg = getPreviousPayrollPeriod($payment_date,$employee->user_id)){
                                $month_15 = $previous_payreg->accumulated;
                                $total_accumulated += $previous_payreg->accumulated;
                            }else{
                                $month_15 = 0;
                            }
                            //Get previous contributions
                            if($previous_contribution = getPreviousPayrollContribution($payment_date,$employee->user_id)){
                                $reg_ee = $previous_contribution->sss_reg_ee;
                                $mpf_ee = $previous_contribution->sss_mpf_ee;
                                $reg_er = $previous_contribution->sss_reg_er;
                                $mpf_er = $previous_contribution->sss_mpf_er;
                                $ec = $previous_contribution->sss_ec;
                                $month_15_phic_ee = $previous_contribution->phic_ee;
                                $month_15_hdmf_ee = $previous_contribution->hdmf_ee;
                            }
                        }else{
                            if(!$is_consultant){
                                $phic_ee = computePHICContribution($rate,'employee_share_ee');
                                $hdmf_ee = $is_monthly ? computePagibigContribution($rate,'employee_share_ee') : 200;
                            }
                        }

                        //Loans&deductions
                        $sss_salary_loan = 0;
                        $sss_calamity_loan = 0;
                        $hdmf_salary_loan = 0;
                        $hdmf_calamity_loan = 0;
                        $salary_deduction_taxable = 0;
                        $salary_deduction_nontaxable = 0;
                        $company_loan = 0;
                        $omhas_loan = 0;
                        $coop_cbu = 0;
                        $coop_mescco = 0;
                        $petty_cash_mescco = 0;
                        $others = 0;

                        if(!$is_consultant){
                            // SSS contribution
                            $sss_reg_ee = computeSSSContribution($total_accumulated,$cut_off,'employee_share_ee',$reg_ee);
                            $sss_mpf_ee = computeSSSContribution($total_accumulated,$cut_off,'mpf_ee',$mpf_ee);
                            $sss_reg_er = computeSSSContribution($total_accumulated,$cut_off,'employer_share_er',$reg_er);
                            $sss_mpf_er = computeSSSContribution($total_accumulated,$cut_off,'mpf_er',$mpf_er);
                            $sss_ec = computeSSSecContribution($total_accumulated,$cut_off,'sss_ec',$ec);
                            $sss_salary_loan = getUserDeductionAmount($employee->user_id,1,$payroll_period->payroll_cutoff);
                            $sss_calamity_loan = getUserDeductionAmount($employee->user_id,2,$payroll_period->payroll_cutoff);
                            $hdmf_salary_loan = getUserDeductionAmount($employee->user_id,3,$payroll_period->payroll_cutoff);
                            $hdmf_calamity_loan = getUserDeductionAmount($employee->user_id,4,$payroll_period->payroll_cutoff);
                        }
                        $salary_deduction_taxable = getUserDeductionAmount($employee->user_id,6,$payroll_period->payroll_cutoff);
                        $salary_deduction_nontaxable = getUserDeductionAmount($employee->user_id,7,$payroll_period->payroll_cutoff);
                        $company_loan = getUserDeductionAmount($employee->user_id,5,$payroll_period->payroll_cutoff);
                        $omhas_loan = getUserDeductionAmount($employee->user_id,8,$payroll_period->payroll_cutoff);
                        $coop_cbu = getUserDeductionAmount($employee->user_id,9,$payroll_period->payroll_cutoff);
                        $coop_regular_loan = getUserDeductionAmount($employee->user_id,10,$payroll_period->payroll_cutoff);
                        $coop_mescco = getUserDeductionAmount($employee->user_id,11,$payroll_period->payroll_cutoff);
                        $petty_cash_mescco = getUserDeductionAmount($employee->user_id,12,$payroll_period->payroll_cutoff);
                        $others = getUserDeductionAmount($employee->user_id,13,$payroll_period->payroll_cutoff);

                        $total_taxable = getUserTotalTaxableAmount(
                            $basic_pay,
                            $payroll_register->absences_amount,
                            $payroll_register->lates_amount,
                            $payroll_register->undertime_amount,
                            $payroll_register->salary_adjustment,
                            $payroll_register->overtime_pay,
                            $sss_reg_ee,
                            $sss_mpf_ee,
                            $phic_ee,
                            $hdmf_ee,
                            $salary_deduction_taxable
                        );
                        $total_taxable = $total_taxable < 0 ?  0 : $total_taxable;

                        //Witholding tax
                        $withholding_tax = getUserWitholdingTaxAmount(
                            $employee->user_id,
                            $total_taxable
                        );

                        $total_deduction = (
                            $withholding_tax +
                            $sss_reg_ee +
                            $sss_mpf_ee +
                            $phic_ee +
                            $hdmf_ee +
                            $hdmf_salary_loan +
                            $hdmf_calamity_loan +
                            $sss_salary_loan +
                            $sss_calamity_loan +
                            $salary_deduction_taxable +
                            $salary_deduction_nontaxable +
                            $company_loan +
                            $omhas_loan +
                            $coop_cbu +
                            $coop_mescco +
                            $petty_cash_mescco +
                            $others
                        );


                        $payroll_register->monthly_basic_pay = $rate ? $rate : 0;
                        $payroll_register->daily_rate = $daily_rate;
                        $payroll_register->basic_pay = $basic_pay;
                        
                        $payroll_register->absences_amount = $absences_amount;
                        $payroll_register->lates_amount = $lates_amount;
                        $payroll_register->undertime_amount = $undertime_amount;

                        $payroll_register->salary_adjustment = $salary_adjustment; //Salary Adjustment
                        $payroll_register->overtime_pay = $overtime_amount; // Overtime

                        // Allowances
                        $payroll_register->meal_allowance = getUserAllowanceAmount($employee->user_id,3,$payroll_period->payroll_cutoff,$no_of_days_worked);
                        $payroll_register->salary_allowance = getUserAllowanceAmount($employee->user_id,4,$payroll_period->payroll_cutoff,$no_of_days_worked);
                        $payroll_register->out_of_town_allowance = getUserAllowanceAmount($employee->user_id,2,$payroll_period->payroll_cutoff,$no_of_days_worked);
                        $payroll_register->incentives_allowance = getUserAllowanceAmount($employee->user_id,5,$payroll_period->payroll_cutoff,$no_of_days_worked);
                        $payroll_register->relocation_allowance = getUserAllowanceAmount($employee->user_id,6,$payroll_period->payroll_cutoff,$no_of_days_worked);
                        $payroll_register->discretionary_allowance = getUserAllowanceAmount($employee->user_id,7,$payroll_period->payroll_cutoff,$no_of_days_worked);
                        $payroll_register->transport_allowance = getUserAllowanceAmount($employee->user_id,8,$payroll_period->payroll_cutoff,$no_of_days_worked);
                        $payroll_register->load_allowance = getUserAllowanceAmount($employee->user_id,9,$payroll_period->payroll_cutoff,$no_of_days_worked);

                        //Witholding tax
                        $payroll_register->withholding_tax = $withholding_tax;
                        // Loans Deductions
                        $payroll_register->sss_salary_loan = $sss_salary_loan;
                        $payroll_register->sss_calamity_loan = $sss_calamity_loan;
                        $payroll_register->hdmf_salary_loan = $hdmf_salary_loan;
                        $payroll_register->hdmf_calamity_loan = $hdmf_calamity_loan;
                        $payroll_register->salary_deduction_taxable = $salary_deduction_taxable;
                        $payroll_register->salary_deduction_nontaxable = $salary_deduction_nontaxable;
                        $payroll_register->company_loan = $company_loan;
                        $payroll_register->omhas_loan = $omhas_loan;
                        $payroll_register->coop_cbu = $coop_cbu;
                        $payroll_register->coop_regular_loan = $coop_regular_loan;
                        $payroll_register->coop_mescco = $coop_mescco;
                        $payroll_register->petty_cash_mescco = $petty_cash_mescco;
                        $payroll_register->others = $others;

                        //Payroll Contributions
                        $payroll_register->sss_reg_ee_15 = $sss_reg_ee;
                        $payroll_register->sss_mpf_ee_15 = $sss_mpf_ee;
                        $payroll_register->phic_ee_15 = $phic_ee;
                        $payroll_register->hmdf_ee_15 = $hdmf_ee;

                        //Gross Pay
                        $grosspay = getUserGrossPayAmount(
                            $basic_pay,
                            $payroll_register->absences_amount,
                            $payroll_register->lates_amount,
                            $payroll_register->undertime_amount,
                            $payroll_register->salary_adjustment,
                            $payroll_register->overtime_pay,
                            $payroll_register->meal_allowance,
                            $payroll_register->salary_allowance,
                            $payroll_register->out_of_town_allowance,
                            $payroll_register->incentives_allowance,
                            $payroll_register->relocation_allowance,
                            $payroll_register->discretionary_allowance,
                            $payroll_register->transport_allowance,
                            $payroll_register->load_allowance
                        );
                        $netpay = ($grosspay - $total_deduction);
                        $payroll_register->grosspay = $grosspay;
                        $payroll_register->total_deduction = $total_deduction;
                        $payroll_register->netpay = $netpay < 0 ? 0 : $netpay;

                        //Total Taxable
                        $payroll_register->total_taxable = $total_taxable;
                        $payroll_register->minimum_wage = $employee->tax_application === "Non-Minimum" ? 0 : 1;
                        $payroll_register->tax_application = $employee->tax_application;

                        //Government contributions number
                        $payroll_register->sss_no = $employee->sss_number;
                        $payroll_register->philhealth_no = $employee->phil_number;
                        $payroll_register->pagibig_no = $employee->hdmf_number;
                        $payroll_register->tin_no = $employee->tax_number;
                        $payroll_register->bir_tagging = $employee->level_info->name ;

                        $payroll_register->sss_reg_er_15 = $sss_reg_er;
                        $payroll_register->sss_mpf_er_15 = $sss_mpf_er;
                        $payroll_register->sss_ec_15 = $sss_ec;
                        $payroll_register->phic_er_15 = $phic_ee;
                        $payroll_register->hdmf_er_15 = $hdmf_ee;
                        $payroll_register->bank = $employee->bank_account_number;
                        $payroll_register->status = $employee->status;
                        $payroll_register->month_15 = $month_15;
                        $payroll_register->month_30 = $month_30;
                        $payroll_register->accumulated = $total_accumulated;


                        if($is_payroll_register_exist == 1){
                            if($payroll_register->posting_status == 'Unposted'){
                                $payroll_register->save();
                                $count++;
                                $this->generateEmployeeContribution($payroll_register,$cut_off);
                            }
                        }else{
                            $payroll_register->save();
                            $count++;
                            $this->generateEmployeeContribution($payroll_register,$cut_off);   
                        }
                        
                        
                       
                    }
                }
            }
        }

        Alert::success('Successfully Generated (' . $count. ')')->persistent('Dismiss');
        return redirect('/pay-reg?payroll_period=' . $request->payroll_period . '&company=' .$request->company . '&department=' .$request->department);

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
    public function addPayrollRemarks(Request $request, $id)
    {
        $payroll_register = PayrollRegister::where('id',$id)->first();

        if($payroll_register){
            
            $payroll_register->remarks = $request->remarks;
            $payroll_register->save();

            Alert::success('Remarks has been successfully saved')->persistent('Dismiss');
            return redirect('/pay-reg?payroll_period=' . $request->payroll_period . '&company=' .$request->company . '&department=' .$request->department);
        }
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

    /**
     * Export to excel
     *
     */
    public function export(Request $request){
        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";
        $payroll_period = isset($request->payroll_period) ? $request->payroll_period : "";
        $company_detail = Company::where('id',$company)->first();

        $company_code = $company_detail ? $company_detail->company_code : "";
        $payroll_period_detail = PayrollPeriod::where('id',$payroll_period)->first();
        return Excel::download(new PayrollRegisterExport($company,$department,$payroll_period), $company_code. ' Payroll Register Export '.$payroll_period_detail->payroll_name.'.xlsx');
    }



    public function import(Request $request){
        ini_set('memory_limit', '-1');
        
        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray(new PayrollRegisterImport, $request->file('file'));

        if(count($data[0]) > 0)
        {
            $save_count = 0;
            $not_save = [];
            foreach($data[0] as $key => $value)
            {
                $payroll_period = PayrollPeriod::where('id',$value['payroll_period_id'])->first();

                $payroll_register = PayrollRegister::where('user_id',$value['user_id'])
                    ->where('payroll_period_id',$payroll_period->id)
                    ->first();

                if(!$payroll_register) $payroll_register = new PayrollRegister();

                    if (isset($value['bank_account'])) $payroll_register->bank_account = $value['bank_account'];
                    if (isset($value['name'])) $payroll_register->name = $value['name'];
                    if (isset($value['position'])) $payroll_register->position = $value['position'];
                    if (isset($value['employment_status'])) $payroll_register->employment_status = $value['employment_status'];
                    if (isset($value['company'])) $payroll_register->company = $value['company'];
                    if (isset($value['department'])) $payroll_register->department = $value['department'];
                    if (isset($value['project'])) $payroll_register->project = $value['project'];
                    
                    if(isset($value['date_hired'])){
                        $date_hired = $value['date_hired'];
                        if($date_hired > 0){
                            $convert_date = ($date_hired - 25569) * 86400;
                            $payroll_register->date_hired = date('Y-m-d', $date_hired);
                        }
                    }

                    if (isset($value['payroll_period_id'])) $payroll_register->payroll_period_id = $value['payroll_period_id'];
                    if(isset($value['cut_from'])){
                        $cut_from = $value['cut_from'];
                        if($cut_from > 0){
                            $convert_date = ($cut_from - 25569) * 86400;
                            $payroll_register->cut_from = date('Y-m-d', $cut_from);
                        }
                    }

                    if(isset($value['cut_to'])){
                        $cut_to = $value['cut_to'];
                        if($cut_to > 0){
                            $convert_date = ($cut_to - 25569) * 86400;
                            $payroll_register->cut_to = date('Y-m-d', $cut_to);
                        }
                    }

                    if (isset($value['monthly_basic_pay'])) $payroll_register->monthly_basic_pay = $value['monthly_basic_pay'];
                    if (isset($value['daily_rate'])) $payroll_register->daily_rate = $value['daily_rate'];
                    if (isset($value['basic_pay'])) $payroll_register->basic_pay = $value['basic_pay'];
                    if (isset($value['absences_amount'])) $payroll_register->absences_amount = $value['absences_amount'];
                    if (isset($value['lates_amount'])) $payroll_register->lates_amount = $value['lates_amount'];
                    if (isset($value['undertime_amount'])) $payroll_register->undertime_amount = $value['undertime_amount'];
                    if (isset($value['salary_adjustment'])) $payroll_register->salary_adjustment = $value['salary_adjustment'];
                    if (isset($value['overtime_pay'])) $payroll_register->overtime_pay = $value['overtime_pay'];
                    if (isset($value['meal_allowance'])) $payroll_register->meal_allowance = $value['meal_allowance'];
                    if (isset($value['salary_allowance'])) $payroll_register->salary_allowance = $value['salary_allowance'];
                    if (isset($value['out_of_town_allowance'])) $payroll_register->out_of_town_allowance = $value['out_of_town_allowance'];
                    if (isset($value['incentives_allowance'])) $payroll_register->incentives_allowance = $value['incentives_allowance'];
                    if (isset($value['relocation_allowance'])) $payroll_register->relocation_allowance = $value['relocation_allowance'];
                    if (isset($value['discretionary_allowance'])) $payroll_register->discretionary_allowance = $value['discretionary_allowance'];
                    if (isset($value['transport_allowance'])) $payroll_register->transport_allowance = $value['transport_allowance'];
                    if (isset($value['load_allowance'])) $payroll_register->load_allowance = $value['load_allowance'];
                    if (isset($value['grosspay'])) $payroll_register->grosspay = $value['grosspay'];
                    if (isset($value['total_taxable'])) $payroll_register->total_taxable = $value['total_taxable'];
                    if (isset($value['minimum_wage'])) $payroll_register->minimum_wage = $value['minimum_wage'];
                    if (isset($value['tax_application'])) $payroll_register->tax_application = $value['tax_application'];
                    if (isset($value['withholding_tax'])) $payroll_register->withholding_tax = $value['withholding_tax'];
                    if (isset($value['sss_reg_ee_15'])) $payroll_register->sss_reg_ee_15 = $value['sss_reg_ee_15'];
                    if (isset($value['sss_mpf_ee_15'])) $payroll_register->sss_mpf_ee_15 = $value['sss_mpf_ee_15'];
                    if (isset($value['phic_ee_15'])) $payroll_register->phic_ee_15 = $value['phic_ee_15'];
                    if (isset($value['hmdf_ee_15'])) $payroll_register->hmdf_ee_15 = $value['hmdf_ee_15'];
                    if (isset($value['hdmf_salary_loan'])) $payroll_register->hdmf_salary_loan = $value['hdmf_salary_loan'];
                    if (isset($value['hdmf_calamity_loan'])) $payroll_register->hdmf_calamity_loan = $value['hdmf_calamity_loan'];
                    if (isset($value['sss_salary_loan'])) $payroll_register->sss_salary_loan = $value['sss_salary_loan'];
                    if (isset($value['sss_calamity_loan'])) $payroll_register->sss_calamity_loan = $value['sss_calamity_loan'];
                    if (isset($value['salary_deduction_taxable'])) $payroll_register->salary_deduction_taxable = $value['salary_deduction_taxable'];
                    if (isset($value['salary_deduction_nontaxable'])) $payroll_register->salary_deduction_nontaxable = $value['salary_deduction_nontaxable'];
                    if (isset($value['company_loan'])) $payroll_register->company_loan = $value['company_loan'];
                    if (isset($value['omhas_loan'])) $payroll_register->omhas_loan = $value['omhas_loan'];
                    if (isset($value['coop_regular_loan'])) $payroll_register->coop_regular_loan = $value['coop_regular_loan'];
                    if (isset($value['coop_mescco'])) $payroll_register->coop_mescco = $value['coop_mescco'];
                    if (isset($value['petty_cash_mescco'])) $payroll_register->petty_cash_mescco = $value['petty_cash_mescco'];
                    if (isset($value['others'])) $payroll_register->others = $value['others'];
                    if (isset($value['total_deduction'])) $payroll_register->total_deduction = $value['total_deduction'];
                    if (isset($value['netpay'])) $payroll_register->netpay = $value['netpay'];
                    if (isset($value['sss_reg_er_15'])) $payroll_register->sss_reg_er_15 = $value['sss_reg_er_15'];
                    if (isset($value['sss_mpf_er_15'])) $payroll_register->sss_mpf_er_15 = $value['sss_mpf_er_15'];
                    if (isset($value['sss_ec_15'])) $payroll_register->sss_ec_15 = $value['sss_ec_15'];
                    if (isset($value['phic_er_15'])) $payroll_register->phic_er_15 = $value['phic_er_15'];
                    if (isset($value['hdmf_er_15'])) $payroll_register->hdmf_er_15 = $value['hdmf_er_15'];
                    if (isset($value['bank'])) $payroll_register->bank = $value['bank'];
                    if (isset($value['status'])) $payroll_register->status = $value['status'];
                    if (isset($value['remarks'])) $payroll_register->remarks = $value['remarks'];
                    if (isset($value['status_last_payroll'])) $payroll_register->status_last_payroll = $value['status_last_payroll'];
                    if (isset($value['sss_no'])) $payroll_register->sss_no = $value['sss_no'];
                    if (isset($value['philhealth_no'])) $payroll_register->philhealth_no = $value['philhealth_no'];
                    if (isset($value['pagibig_no'])) $payroll_register->pagibig_no = $value['pagibig_no'];
                    if (isset($value['tin_no'])) $payroll_register->tin_no = $value['tin_no'];
                    if (isset($value['bir_tagging'])) $payroll_register->bir_tagging = $value['bir_tagging'];
                    if (isset($value['month_15'])) $payroll_register->month_15 = $value['month_15'];
                    if (isset($value['month_30'])) $payroll_register->month_30 = $value['month_30'];
                    if (isset($value['accumulated'])) $payroll_register->accumulated = $value['accumulated'];
                    if (isset($value['number'])) $payroll_register->number = $value['number'];
                    if (isset($value['posting_status'])) $payroll_register->posting_status = $value['posting_status'];
                    if (isset($value['created_by'])) $payroll_register->created_by = $value['created_by'];

                    $payroll_register->save();
                    $save_count+=1;                                        
            }

            Alert::success('Successfully Import Payroll Registers (' . $save_count. ')')->persistent('Dismiss');

            return redirect('pay-reg');
        }
    }
    
    public function generateEmployeeContribution($payroll_register,$payment_schedule){
        PayrollEmployeeContribution::updateOrCreate(
            [
                'user_id' => $payroll_register->user_id,
                'payroll_period_id' => $payroll_register->payroll_period_id
            ],
            [
                'user_id' => $payroll_register->user_id,
                'payroll_period_id' => $payroll_register->payroll_period_id,
                'company' => $payroll_register->company,
                'sss_reg_ee' => $payroll_register->sss_reg_ee_15,
                'sss_mpf_ee' => $payroll_register->sss_mpf_ee_15,
                'phic_ee' => $payroll_register->phic_ee_15,
                'hdmf_ee' => $payroll_register->hmdf_ee_15,
                'sss_reg_er' => $payroll_register->sss_reg_er_15,
                'sss_mpf_er' => $payroll_register->sss_mpf_er_15,
                'sss_ec' => $payroll_register->sss_ec_15,
                'phic_er' => $payroll_register->phic_er_15,
                'hdmf_er' => $payroll_register->hdmf_er_15,
                'payment_schedule' => $payment_schedule
            ]
        );
    }

    public function post(Request $request){
        $company = $request->company;
        $allowed_companies = getUserAllowedPayrollCompanies(auth()->user()->id);

        $payroll_registers = PayrollRegister::whereHas('employee',function($q) use($company,$allowed_companies){
                $q->when($company != "All",function($q) use($company){
                    $q->where('company_id',$company);
                })
                ->when($company == "All",function($q) use($allowed_companies){
                    $q->whereIn('company_id',$allowed_companies);
                });
            })
            ->where('payroll_period_id',$request->payroll_period)
            ->when(isset($request->department),function($q) use($request){
                $q->whereHas('employee',function($q) use($request){
                    $q->where('department_id',$request->department);
                });
            })
            ->when(isset($request->payreg_id),function($q) use($request){
                $q->where('id',$request->payreg_id);
            })
            ->get();

            foreach($payroll_registers as $payroll_register){
                $user = User::findOrFail($payroll_register->user_id);
                //Send email notif for the payslip            
                // if($request->posting_status == 'Posted') Mail::to($user->email)->send(new PayslipNotification($payroll_register->id,$payroll_register->cut_from,$payroll_register->cut_to));

                $payroll_register->update([
                    'posting_status' => $request->posting_status,
                    'posting_date' => Carbon::now()
                ]);
            }

            Alert::success('Payroll Register Successfully '. $request->posting_status. ' (' . $payroll_registers->count(). ')')->persistent('Dismiss');
            return redirect('/pay-reg?payroll_period=' . $request->payroll_period . '&company=' .$request->company . '&department=' .$request->department);
    }
}
