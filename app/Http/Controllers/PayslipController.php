<?php

namespace App\Http\Controllers;
use Excel;
use App\Payroll;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use App\AttSummary;

class PayslipController extends Controller
{
    //
    public function view ()
    {
      
        return view('payslips.payslips',
        array(
            'header' => 'payslips',
            
        ));
    }
    public function payroll_datas()
    {
        $payrolls = Payroll::select('date_from','date_to','auditdate','created_at')->orderBy('date_from','desc')->get()->unique('date_from');
        $payroll_employees = Payroll::orderBy('name','asc')->get();
        $attendances =  AttSummary::get();
        // dd($payrolls);
        return view('payroll.pay_reg',
        array(
            'header' => 'Payroll',
            'payrolls' => $payrolls,
            'payroll_employees' => $payroll_employees,
            'attendances' => $attendances,
            
        ));
    }
    function import(Request $request)
    {
     $path = $request->file('file')->getRealPath();

     $data = Excel::load($path)->get();
     if($data->count() > 0)
     {
        // dd($data);
      foreach($data->toArray() as $key => $value)
      {
        $payroll = new Payroll;
        $payroll->emp_code  = $value['emp_code'];
        $payroll->bank_acctno  = $value['bank_acctno'];
        $payroll->bank  = $value['bank'];
        $payroll->name  = $value['name'];
        $payroll->position  = $value['position'];
        $payroll->emp_status  = $value['emp_status'];
        $payroll->company  = $value['company'];
        $payroll->department  = $value['department'];
        $payroll->location  = $value['location'];
        $payroll->date_hired  = $value['date_hired'];
        $payroll->date_from  = $value['date_from'];
        $payroll->date_to  = $value['date_to'];
        $payroll->month_pay  = $value['month_pay'];
        $payroll->daily_pay  = $value['daily_pay'];
        $payroll->semi_month_pay  = $value['semi_month_pay'];
        $payroll->absences  = $value['absences'];
        $payroll->late  = $value['late'];
        $payroll->undertime  = $value['undertime'];
        $payroll->total_adjstmenthrs  = $value['total_adjstmenthrs'];
        $payroll->salary_adjustment  = $value['salary_adjustment'];
        $payroll->overtime  = $value['overtime'];
        $payroll->meal_allowance  = $value['meal_allowance'];
        $payroll->salary_allowance  = $value['salary_allowance'];
        $payroll->oot_allowance  = $value['oot_allowance'];
        $payroll->inc_allowance  = $value['inc_allowance'];
        $payroll->rel_allowance  = $value['rel_allowance'];
        $payroll->disc_allowance  = $value['disc_allowance'];
        $payroll->trans_allowance  = $value['trans_allowance'];
        $payroll->load_allowance  = $value['load_allowance'];
        $payroll->sick_leave  = $value['sick_leave'];
        $payroll->vacation_leave  = $value['vacation_leave'];
        $payroll->wfhome  = $value['wfhome'];
        $payroll->offbusiness  = $value['offbusiness'];
        $payroll->sick_leave_nopay  = $value['sick_leave_nopay'];
        $payroll->vacation_leave_nopay  = $value['vacation_leave_nopay'];
        $payroll->gross_pay  = $value['gross_pay'];
        $payroll->total_taxable  = $value['total_taxable'];
        $payroll->witholding_tax  = $value['witholding_tax'];
        $payroll->sss_regee  = $value['sss_regee'];
        $payroll->sss_mpfee = $value['sss_mpfee'];
        $payroll->phic_ee  = $value['phic_ee'];
        $payroll->hdmf_ee  = $value['hdmf_ee'];
        $payroll->hdmf_sal_loan  = $value['hdmf_sal_loan'];
        $payroll->hdmf_cal_loan  = $value['hdmf_cal_loan'];
        $payroll->sss_sal_loan  = $value['sss_sal_loan'];
        $payroll->sss_cal_loan  = $value['sss_cal_loan'];
        $payroll->sal_ded_tax  = $value['sal_ded_tax'];
        $payroll->sal_ded_nontax  = $value['sal_ded_nontax'];
        $payroll->sal_loan  = $value['sal_loan'];
        $payroll->com_loan  = $value['com_loan'];
        $payroll->omhas  = $value['omhas'];
        $payroll->coop_cbu  = $value['coop_cbu'];
        $payroll->coop_reg_loan  = $value['coop_reg_loan'];
        $payroll->coop_messco  = $value['coop_messco'];
        $payroll->uploan  = $value['uploan'];
        $payroll->others  = $value['others'];
        $payroll->total_deduction  = $value['total_deduction'];
        $payroll->netpay  = $value['netpay'];
        $payroll->sss_reg_er  = $value['sss_reg_er'];
        $payroll->sss_mpf_er  = $value['sss_mpf_er'];
        $payroll->sss_ec  = $value['sss_ec'];
        $payroll->phic_er  = $value['phic_er'];
        $payroll->hdmf_er  = $value['hdmf_er'];
        $payroll->payroll_status  = $value['payroll_status'];
        $payroll->tin_no  = $value['tin_no'];
        $payroll->phil_no = $value['phil_no'];
        $payroll->pagibig_no  = $value['pagibig_no'];
        $payroll->sss_no  = $value['sss_no'];
        $payroll->save();
      }
    }
    
    
     Alert::success('Successfully Import')->persistent('Dismiss');
     return back();
    }
}
