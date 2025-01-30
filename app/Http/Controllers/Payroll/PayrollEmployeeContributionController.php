<?php


namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\PayrollEmployeeContribution;
use App\PayrollPeriod;
use App\Employee;
use App\Company;
use App\Imports\PayrollEmployeeContributionImport;
use App\Exports\PayrollEmployeeContributionExport;
use Excel;

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

        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);


        $companies = Company::whereHas('employee_has_company')
                                ->whereIn('id',$allowed_companies)
                                ->get();

        $payroll_periods = PayrollPeriod::get();

        $company = isset($request->company) ? $request->company : "";
        $payroll_period = isset($request->payroll_period) ? $request->payroll_period : "";

        $contributions = PayrollEmployeeContribution::whereHas('employee',function($q) use($allowed_companies){
                                                        $q->whereIn('company_id',$allowed_companies);
                                                    })
                                                    ->with('employee.company','payrollPeriod');
        if($company){
            $contributions = $contributions->whereHas('employee',function($q) use($company){
                $q->where('company_id',$company);
            });
        }

        if($payroll_period){
            $contributions = $contributions->whereHas('employee',function($q) use($payroll_period){
                $q->where('payroll_period_id',$payroll_period);
            });
        }

        $contributions = $contributions->get();

        $employees = Employee::select('user_id','first_name','last_name')->where('status','Active')->get();


        return view(
            'payroll_employee_contributions.index',
            array(
                'header' => 'contributions',
                'contributions' => $contributions,
                'employees' => $employees,
                'payroll_periods' => $payroll_periods,
                'payroll_period' => $payroll_period,
                'companies' => $companies,
                'company' => $company
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

    public function import(Request $request){

        ini_set('memory_limit', '-1');
        
        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray(new PayrollEmployeeContributionImport, $request->file('file'));

        $company = isset($request->company) ?  $request->company : null;

        if(count($data[0]) > 0)
        {
            $save_count = 0;
            $not_save = [];
            foreach($data[0] as $key => $value)
            {
                $payroll = PayrollEmployeeContribution::where('user_id',$value['user_id'])->first();

                if($payroll){
                    if (isset($value['user_id'])) {
                        $payroll->user_id = $value['user_id'];
                    }
                    if (isset($value['sss_reg_ee'])) {
                        $payroll->sss_reg_ee = $value['sss_reg_ee'];
                    }
                    if (isset($value['sss_mpf_ee'])) {
                        $payroll->sss_mpf_ee = $value['sss_mpf_ee'];
                    }
                    if (isset($value['phic_ee'])) {
                        $payroll->phic_ee = $value['phic_ee'];
                    }
                    if (isset($value['hdmf_ee'])) {
                        $payroll->hdmf_ee = $value['hdmf_ee'];
                    }
                    if (isset($value['sss_reg_er'])) {
                        $payroll->sss_reg_er = $value['sss_reg_er'];
                    }
                    if (isset($value['sss_mpf_er'])) {
                        $payroll->sss_mpf_er = $value['sss_mpf_er'];
                    }
                    if (isset($value['sss_ec'])) {
                        $payroll->sss_ec = $value['sss_ec'];
                    }
                    if (isset($value['phic_er'])) {
                        $payroll->phic_er = $value['phic_er'];
                    }
                    if (isset($value['hdmf_er'])) {
                        $payroll->hdmf_er = $value['hdmf_er'];
                    }
                    if (isset($value['payment_schedule'])) {
                        $payroll->payment_schedule = $value['payment_schedule'];
                    }
                    $payroll->save();
                    $save_count+=1;
                }else{
                    $payroll = PayrollEmployeeContribution::findOrFail($id);
                    $payroll->user_id = $value['user_id'];
                    $payroll->sss_reg_ee = $value['sss_reg_ee'];
                    $payroll->sss_mpf_ee = $value['sss_mpf_ee'];
                    $payroll->phic_ee = $value['phic_ee'];
                    $payroll->hdmf_ee = $value['hdmf_ee'];
                    $payroll->sss_reg_er = $value['sss_reg_er'];
                    $payroll->sss_mpf_er = $value['sss_mpf_er'];
                    $payroll->sss_ec = $value['sss_ec'];
                    $payroll->phic_er = $value['phic_er'];
                    $payroll->hdmf_er = $value['hdmf_er'];
                    $payroll->payment_schedule = $value['payment_schedule'];
                    $payroll->save();

                    $save_count+=1;
                }                                         
            }

            Alert::success('Successfully Import Employee Contributions (' . $save_count. ')')->persistent('Dismiss');

            return redirect('payroll-employee-contributions');

            }
        }
    
    public function generate(){
        $allowed_companies = getUserAllowedCompanies(auth()->user()->id);
        
        $employees = Employee::with('company','department')
                ->whereIn('company_id',$allowed_companies)
                ->where('company_id',$request->company)
                ->when($request->department,function($q) use($request){
                    $q->where('department_id',$request->department);
                })
                ->where('status','Active')
                ->get();

        $count = 0;
        foreach($employees as $employee){

            $employee_contribution = PayrollEmployeeContribution::where('payment_schedule',$request->payment_schedule)
                ->where('user_id',$employee->user_id)
                ->first();


            $absences_amount = 0;
            $lates_amount = 0;
            $undertime_amount = 0;
            $salary_adjustment = getUserSalaryAdjustmentAmount($employee->user_id,$payroll_period->id);
            $ot_amount = getUserOvertime($employee->user_id,$payroll_period->id);
            //IF Monthly
            $rate = $employee->rate ? Crypt::decryptString($employee->rate) : "";
            $basic_pay = $rate ? $rate / 2 : 0; //Basic Pay Computation

            // N3 = Basic pay halfmonth
            // O3 = Absences amount 
            // P3 = Late amount 
            // Q3 = Undertime amount
            // R3 = salary adjustment
            // S3 = overtime amount

            // N3-O3-P3-Q3+R3+S3

            if(empty($employee_contribution)) $employee_contribution = new PayrollEmployeeContribution;
            
            $employee_contribution->sss_reg_ee = computeSSRegEe('ACTIVE',$basic_pay,$cutoff,$firstcutoff_amount, $user_id);
            // $employee_contribution->sss_mpf_ee = $payroll_period->id;
            // $employee_contribution->phic_ee = $payroll_period->id;
            // $employee_contribution->hdmf_ee = $payroll_period->id;
            // $employee_contribution->sss_reg_er = $payroll_period->id;
            // $employee_contribution->sss_mpf_er = $payroll_period->id;
            // $employee_contribution->sss_ec = $payroll_period->id;
            // $employee_contribution->phic_er = $payroll_period->id;
            // $employee_contribution->hdmf_er = $payroll_period->id;
            $employee_contribution->payment_schedule = $request->payment_schedule;
        

            $employee_contribution->save();
            $count++;
            
        }

        Alert::success('Successfully Generated (' . $count. ')')->persistent('Dismiss');
        return redirect('/pay-reg?payroll_period=' . $request->payroll_period . '&company=' .$request->company . '&department=' .$request->department);
    }

    
    /**
     * Export to excel
     *
     */
    public function export(Request $request){
        $company = isset($request->company) ? $request->company : "";
        $company_detail = Company::where('id',$company)->first();

        $company_code = $company_detail ? $company_detail->company_code : "";

        return Excel::download(new PayrollEmployeeContributionExport($company), $company_code. ' Payroll Employee Contribution Export.xlsx');
    }
}
