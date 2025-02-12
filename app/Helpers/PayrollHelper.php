<?php
use App\Employee;
use App\PayrollSalaryAdjustment;
use App\PayrollOvertimeAdjustment;
use App\PayrollAttendance;
use App\PayrollEmployeeContribution;
use App\SssMatrixContribution;
use App\PagibigMatrixContribution;
use App\PhicMatrixContribution;
use App\PayrollRegister;

function getUserWitholdingTaxAmount($user_id,$total_taxable){
    $user = Employee::where('user_id',$user_id)
        ->first();
    $witholding_tax = 0;

    if ($user->tax_application === "Non-Minimum") {
        if ($total_taxable <= 10417) {
            $witholding_tax += 0;
        } 
        if ($total_taxable > 10417 && $total_taxable <= 16666.67) {
            $witholding_tax += (($total_taxable - 10417) * 0.15 + 0);
        } 
        if ($total_taxable > 16666.67 && $total_taxable <= 33333.33) {
            $witholding_tax += (($total_taxable - 16667) * 0.2 + 937.5);
        } 
        if ($total_taxable > 33333.33 && $total_taxable <= 83333.33) {
            $witholding_tax += (($total_taxable - 33333.33) * 0.25 + 4270.7);
        } 
        if ($total_taxable > 83333.33 && $total_taxable <= 333333.33) {
            $witholding_tax += (($total_taxable - 83333.33) * 0.3 + 16770.7);
        } 
        if ($total_taxable > 333333.33) {
            $witholding_tax += (($total_taxable - 333333.33) * 0.35 + 91770.7);
        }
    }

    return $witholding_tax;
}

function getUserSalaryAdjustmentAmount($user_id,$payroll_period_id){
    $adjustment = PayrollSalaryAdjustment::where('payroll_period_id',$payroll_period_id)
        ->where('user_id',$user_id)
        ->where('status','Active')
        ->first();

    return $adjustment ? ($adjustment->type == "Addition" ? $adjustment->amount : $adjustment->amount * -1) : 0;
}

function getUserGrossPayAmount($basic_pay,$absences_amount,$lates_amount,$undertime_amount,$salary_adjustment,$ot_amount,
    $meal_allowances,$salary_allowances,$out_allowances,$incentives_allowances,$reallocation_allowances,$discretionary_allowances,$transpo_allowances,$load_allowances){

    return $basic_pay 
            - $absences_amount 
            - $lates_amount 
            - $undertime_amount 
            + $salary_adjustment 
            + $ot_amount 
            + $meal_allowances
            + $salary_allowances 
            + $out_allowances 
            + $incentives_allowances 
            + $reallocation_allowances 
            + $discretionary_allowances 
            + $transpo_allowances 
            + $load_allowances;
}

function getUserTotalTaxableAmount($basic_pay,$absences_amount,$lates_amount,$undertime_amount,$salary_adjustment,$ot_amount,
    $sss_reg_ee,$sss_mpf_ee,$phic_ee,$hdmf_ee,$salary_deduction_taxable){

    return $basic_pay - $absences_amount - $lates_amount - $undertime_amount + $salary_adjustment + $ot_amount - $sss_reg_ee - $sss_mpf_ee - $phic_ee - $hdmf_ee - $salary_deduction_taxable;
}

function getUserOvertimeAdjustmentAmount($user_id,$payroll_period_id){
    $adjustment = PayrollOvertimeAdjustment::where('payroll_period_id',$payroll_period_id)
        ->where('user_id',$user_id)
        ->where('status','Active')
        ->first();
    
    return $adjustment ? ($adjustment->type == "Addition" ? $adjustment->amount : $adjustment->amount * -1) : 0;
}

function getUserOvertime($user_id,$payroll_period_id){
    return PayrollAttendance::where('payroll_period_id',$payroll_period_id)
        ->where('user_id',$user_id)
        ->sum('total_overtime_pay');
}

function getUserAbsencesAmount($user_id,$payroll_period_id){
    return PayrollAttendance::where('payroll_period_id',$payroll_period_id)
    ->where('user_id',$user_id)
    ->sum('absences_amount');
}

function getUserNoOfDaysWorked($user_id,$payroll_period_id){
    $payroll_attendance = PayrollAttendance::select('no_of_days_worked')->where('payroll_period_id',$payroll_period_id)
                                                ->where('user_id',$user_id)->first();
    if($payroll_attendance){
        return $payroll_attendance->no_of_days_worked;
    }
}


function getUserLatesAmount($user_id,$payroll_period_id){
    return PayrollAttendance::where('payroll_period_id',$payroll_period_id)
    ->where('user_id',$user_id)
    ->sum('lates_amount');
}

function getUserUndertimeAmount($user_id,$payroll_period_id){
    return PayrollAttendance::where('payroll_period_id',$payroll_period_id)
    ->where('user_id',$user_id)
    ->sum('undertime_amount');
}

function getSSSRegEE($user_id,$cutoff){
    return PayrollEmployeeContribution::where('payment_schedule',$cutoff)
    ->where('user_id',$user_id)
    ->sum('sss_reg_ee');
}

function getSSSMPFEE($user_id,$cutoff){
    return PayrollEmployeeContribution::where('payment_schedule',$cutoff)
    ->where('user_id',$user_id)
    ->sum('sss_mpf_ee');
}

function getPHICEE($user_id,$cutoff){
    return PayrollEmployeeContribution::where('payment_schedule',$cutoff)
    ->where('user_id',$user_id)
    ->sum('phic_ee');
}

function getHDFMEE($user_id,$cutoff){
    return PayrollEmployeeContribution::where('payment_schedule',$cutoff)
    ->where('user_id',$user_id)
    ->sum('hdmf_ee');
}

function getSSSRegER($user_id,$cutoff){
    return PayrollEmployeeContribution::where('payment_schedule',$cutoff)
    ->where('user_id',$user_id)
    ->sum('sss_reg_er');
}

function getSSSMpfER($user_id,$cutoff){
    return PayrollEmployeeContribution::where('payment_schedule',$cutoff)
    ->where('user_id',$user_id)
    ->sum('sss_mpf_er');
}

function getSSSEc($user_id,$cutoff){
    return PayrollEmployeeContribution::where('payment_schedule',$cutoff)
    ->where('user_id',$user_id)
    ->sum('sss_ec');
}

function getPHICEr($user_id,$cutoff){
    return PayrollEmployeeContribution::where('payment_schedule',$cutoff)
    ->where('user_id',$user_id)
    ->sum('phic_er');
}

function getHDMFEr($user_id,$cutoff){
    return PayrollEmployeeContribution::where('payment_schedule',$cutoff)
    ->where('user_id',$user_id)
    ->sum('hdmf_er');
}

function computeSSSContribution($accumulated_amount,$cutoff,$field,$firstcutoff_contribution ){

    $highest_contribution = SssMatrixContribution::orderBy('min_salary','desc')->first();

    if($accumulated_amount >= $highest_contribution->min_salary){
        $sss_contribution = $highest_contribution;
    }else{
    // return $accumulated_amount . $field;
        $sss_contribution = SssMatrixContribution::where('min_salary','<=',$accumulated_amount)
            ->where('max_salary','>=',$accumulated_amount)
            ->first();
    }
    if(!$sss_contribution) return 0;
    if($cutoff == 'Second Cut-Off'){
        if($field == 'employee_share_ee' || $field == 'employee_share_er' || $field == 'mpf_ee' || $field == 'mpf_er'){
            return $sss_contribution->$field - $firstcutoff_contribution;
        }else{
            return $sss_contribution->$field;
        }
    }else{
        return $sss_contribution->$field;
    }

    // if(!$sss_contribution) return 0;
    // if ($cutoff == 'Second Cut-Off') return $sss_contribution->$field - $firstcutoff_contribution;

    // return  $sss_contribution->$field;
}

function computeSSSecContribution($accumulated_amount,$cutoff,$field,$firstcutoff_amount){
    $sss_ec = 0;

    $sss_ec += ($accumulated_amount > 0 && $accumulated_amount <= 14749.99) ? 10 : 0;
    $sss_ec += ($accumulated_amount >= 14750) ? 30 : 0;
    
    return  $sss_ec;
}

function computePagibigContribution($monthly_basicpay,$field){
    $highest_contribution = PagibigMatrixContribution::orderBy('min_salary','desc')->first();
    
    if($monthly_basicpay >= $highest_contribution->min_salary) return $highest_contribution->min_salary * $highest_contribution->$field;

    $contribution = PagibigMatrixContribution::where('max_salary','>=',$monthly_basicpay)
        ->where('min_salary','<=',$monthly_basicpay)
        ->first();

    return $monthly_basicpay * $contribution->$field;
}

function computePHICContribution($monthly_basicpay,$field){
    $lowest_contribution = PhicMatrixContribution::orderBy('min_salary','asc')->first();
    $highest_contribution = PhicMatrixContribution::orderBy('min_salary','desc')->first();
    $contribution = 0;
    
    if($monthly_basicpay > 0){
        if($monthly_basicpay <= $lowest_contribution->max_salary) $contribution += $lowest_contribution->total_contribution;
        
        if($contribution == 0){
            if($monthly_basicpay >= $highest_contribution->min_salary){
                $contribution += $highest_contribution->total_contribution;
            }else{
                $phic = PhicMatrixContribution::where('max_salary','>=',$monthly_basicpay)
                    ->where('min_salary','<=',$monthly_basicpay)
                    ->first();
    
                $contribution += ($monthly_basicpay * $phic->$field);
            }
        }
    }
    return $contribution / 2;
}

function getPreviousPayrollPeriod($payment_date,$user_id){
    return PayrollRegister::whereHas('payrollPeriod',function($q) use($payment_date,$user_id){
            $q->whereYear('payment_date',$payment_date->year)
            ->whereMonth('payment_date',$payment_date->month)
            ->where('user_id',$user_id)
            ->where('payroll_cutoff','First Cut-Off');
        })
        ->orderBy('id','desc')
        ->first();
}

function getPreviousPayrollContribution($payment_date,$user_id){
    return PayrollEmployeeContribution::whereHas('payrollPeriod',function($q) use($payment_date,$user_id){
            $q->whereYear('payment_date',$payment_date->year)
            ->whereMonth('payment_date',$payment_date->month)
            ->where('user_id',$user_id);
        })
        ->where('payment_schedule','First Cut-Off')
        ->orderBy('id','desc')   
        ->first();
}



