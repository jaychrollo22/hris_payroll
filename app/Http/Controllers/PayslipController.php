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
        $attendances =  AttSummary::orderBy('employee','asc')->get();
        // dd($payrolls);
        return view('payroll.pay_reg',
        array(
            'header' => 'Payroll',
            'payrolls' => $payrolls,
            'payroll_employees' => $payroll_employees,
            'attendances' => $attendances,
            
        ));
    }
    public function attendances()
    {
        $attendances =  AttSummary::orderBy('employee','asc')->get();
        return view('payroll.timekeeping',
        array(
            'header' => 'Timekeeping',
            'attendances' => $attendances,
            'attendances' => $attendances,
            
        ));
    }
    function upload_attendance(Request $request)
    {
        $path = $request->file('file')->getRealPath();
        $data = Excel::load($path)->get();

        // dd($data);
        if($data->count() > 0)
        {
            // dd($data);
        foreach($data->toArray() as $key => $value)
        {
            $payroll = new AttSummary;
            $payroll->company = $value['company'];
            $payroll->badge_no = $value['badge_no'];
            $payroll->employee = $value['employee'];
            $payroll->location = $value['location'];
            $payroll->period_from = date('Y-m-d',strtotime($value['period_from']));
            $payroll->period_to = date('Y-m-d',strtotime($value['period_to']));
            $payroll->tot_days_absent = $value['tot_days_absent'];
            $payroll->tot_days_work = $value['tot_days_work'];
            $payroll->tot_lates = $value['tot_lates'];
            $payroll->total_adjstmenthrs = $value['total_adjstmenthrs'];
            $payroll->tot_overtime_reg = $value['tot_overtime_reg'];
            $payroll->night_differential = $value['night_differential'];
            $payroll->night_differential_ot = $value['night_differential_ot'];
            $payroll->tot_regholiday = $value['tot_regholiday'];
            $payroll->tot_overtime_regholiday = $value['tot_overtime_regholiday'];
            $payroll->tot_regholiday_nightdiff = $value['tot_regholiday_nightdiff'];
            $payroll->tot_overtime_regholiday_nightdiff = $value['tot_overtime_regholiday_nightdiff'];
            $payroll->tot_spholiday = $value['tot_spholiday'];
            $payroll->tot_overtime_spholiday = $value['tot_overtime_spholiday'];
            $payroll->tot_spholiday_nightdiff = $value['tot_spholiday_nightdiff'];
            $payroll->tot_overtime_spholiday_nightdiff = $value['tot_overtime_spholiday_nightdiff'];
            $payroll->tot_rest = $value['tot_rest'];
            $payroll->tot_overtime_rest = $value['tot_overtime_rest'];
            $payroll->night_differential_rest = $value['night_differential_rest'];
            $payroll->night_differential_ot_rest = $value['night_differential_ot_rest'];
            $payroll->tot_overtime_rest_regholiday = $value['tot_overtime_rest_regholiday'];
            $payroll->night_differential_rest_regholiday = $value['night_differential_rest_regholiday'];
            $payroll->tot_overtime_night_diff_rest_regholiday = $value['tot_overtime_night_diff_rest_regholiday'];
            $payroll->tot_sprestholiday = $value['tot_sprestholiday'];
            $payroll->tot_overtime_sprestholiday = $value['tot_overtime_sprestholiday'];
            $payroll->tot_sprestholiday_nightdiff = $value['tot_sprestholiday_nightdiff'];
            $payroll->tot_overtime_sprestholiday_nightdiff = $value['tot_overtime_sprestholiday_nightdiff'];
            $payroll->total_undertime = $value['total_undertime'];
            $payroll->sick_leave = $value['sick_leave'];
            $payroll->vacation_leave = $value['vacation_leave'];
            $payroll->sick_leave_nopay = $value['sick_leave_nopay'];
            $payroll->vacation_leave_nopay = $value['vacation_leave_nopay'];
            $payroll->workfromhome = $value['workfromhome'];
            $payroll->offbusiness = $value['offbusiness'];
            $payroll->save();
        }
        }
        Alert::success('Successfully Import Attendance')->persistent('Dismiss');
     return back();
    }
    function import(Request $request)
    {
        $path = $request->file('file')->getRealPath();

        $data = Excel::load($path)->get();
        // dd($data);
        if($data->count() > 0)
        {
            // dd($data);
        foreach($data->toArray() as $key => $value)
        {
            if($value['code'] != null)
            {
            $payroll = new Payroll;
            $payroll->emp_code  = $value['code'];
            $payroll->bank_acctno  = $value['bank_account'];
            $payroll->bank  = $value['bank'];
            $payroll->name  = $value['name'];
            $payroll->position  = $value['position'];
            $payroll->emp_status  = $value['employment_status'];
            $payroll->company  = $value['company'];
            $payroll->department  = $value['department'];
            $payroll->location  = $value['location'];
            $payroll->date_hired  = date('Y-m-d',strtotime($value['date_hired']));
            $payroll->date_from  = date('Y-m-d',strtotime($value['cut_off_from']));
            $payroll->date_to  = date('Y-m-d',strtotime($value['cut_off_to']));
            $payroll->month_pay  = $value['monthly_rate'];
            $payroll->daily_pay  = $value['daily_rate'];
            $payroll->semi_month_pay  = $value['semi_monthly_rate'];
            $payroll->absences  = $value['absences'];
            $payroll->late  = $value['lates'];
            $payroll->undertime  = $value['undertime'];
            $payroll->total_adjstmenthrs  = $value['salary_adjustment_hrs'];
            $payroll->salary_adjustment  = $value['salary_adjustment_days'];
            $payroll->overtime  = $value['total_overtime'];
            $payroll->meal_allowance  = $value['meal_allowance'];
            $payroll->salary_allowance  = $value['salary_allowance'];
            $payroll->oot_allowance  = $value['out_of_town_allowance'];
            $payroll->inc_allowance  = $value['incentives_allowance'];
            $payroll->rel_allowance  = $value['relocation_allowance'];
            $payroll->disc_allowance  = $value['discretionary_allowance'];
            $payroll->trans_allowance  = $value['transportation_allowance'];
            $payroll->load_allowance  = $value['load_allowance'];
            $payroll->sick_leave  = $value['sick_leave'];
            $payroll->vacation_leave  = $value['vacation_leave'];
            $payroll->wfhome  = $value['work_from_home'];
            $payroll->offbusiness  = $value['official_business'];
            $payroll->sick_leave_nopay  = $value['sick_leave_no_pay'];
            $payroll->vacation_leave_nopay  = $value['vacation_leave_no_pay'];
            $payroll->gross_pay  = $value['grosspay'];
            $payroll->total_taxable  = $value['total_taxable'];
            $payroll->witholding_tax  = $value['withholding_tax'];
            $payroll->sss_regee  = $value['sss_ee'];
            $payroll->sss_mpfee = $value['sss_mpf_ee'];
            $payroll->phic_ee  = $value['phic_ee'];
            $payroll->hdmf_ee  = $value['hdmf_ee'];
            $payroll->hdmf_sal_loan  = $value['hdmf_salary_loan'];
            $payroll->hdmf_cal_loan  = $value['hdmf_calamity_loan'];
            $payroll->sss_sal_loan  = $value['sss_salary_loan'];
            $payroll->sss_cal_loan  = $value['sss_calamity_loan'];
            $payroll->sal_ded_tax  = $value['salary_deduction_taxable'];
            $payroll->sal_ded_nontax  = $value['salary_deduction_non_taxable'];
            $payroll->sal_loan  = $value['salary_loan'];
            $payroll->com_loan  = $value['company_loan'];
            $payroll->omhas  = $value['omhas'];
            $payroll->coop_cbu  = $value['coop_cbu'];
            $payroll->coop_reg_loan  = $value['coop_regular_loan'];
            $payroll->coop_messco  = $value['coop_mescco'];
            $payroll->uploan  = $value['uploan'];
            $payroll->others  = $value['others'];
            $payroll->total_deduction  = $value['total_deduction'];
            $payroll->netpay  = $value['netpay'];
            $payroll->sss_reg_er  = $value['sss_er'];
            $payroll->sss_mpf_er  = $value['sss_mpf_er'];
            $payroll->sss_ec  = $value['sss_ec'];
            $payroll->phic_er  = $value['phic_er'];
            $payroll->hdmf_er  = $value['hdmf_er'];
            $payroll->payroll_status  = "N";
            $payroll->tin_no  = $value['tin_no.'];
            $payroll->phil_no = $value['philhealth_no.'];
            $payroll->pagibig_no  = $value['pagibig_no.'];
            $payroll->sss_no  = $value['sss_no.'];
            $payroll->save();
            }
        }
        }
    
    
     Alert::success('Successfully Import')->persistent('Dismiss');
     return back();
    }
    function monthly_benefit(Request $request)
    {
        $payrolls = Payroll::select('date_from','date_to')->orderBy('date_from','desc')->get()->unique('date_from');
        
       
    }
}
