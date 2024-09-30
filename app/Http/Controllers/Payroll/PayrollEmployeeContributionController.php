<?php


namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\PayrollEmployeeContribution;
use App\Employee;
use App\Company;

use App\Imports\PayrollEmployeeContributionImport;
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

        $company = isset($request->company) ? $request->company : "";

        $contributions = PayrollEmployeeContribution::whereHas('employee',function($q) use($allowed_companies){
                                                        $q->whereIn('company_id',$allowed_companies);
                                                    })
                                                    ->with('employee.company');
        if($company){
            $contributions = $contributions->whereHas('employee',function($q) use($company){
                $q->where('company_id',$company);
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
                'companies' => $companies,
                'company' => $company,

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
}
