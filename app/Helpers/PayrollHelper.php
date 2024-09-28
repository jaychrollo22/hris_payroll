<?php
use App\Employee;
use App\PayrollSalaryAdjustment;

function getUserWitholdingTaxAmount($user_id,$basic_pay,$lates,$under_time,$salary_adjustment,$overtime_pay,
    $sss_reg_ee,$sss_mpf_ee,$phic_ee,$hdmf_ee,$salary_deduction_taxable){
    $user = Employee::where('user_id',$user_id)
        ->first();
    
    // $basic_pay  = 100000;  
    // $absences  = 10;
    // $lates  = 5;
    // $under_time  = 8;
    // $salary_adjustment  = 15;
    // $overtime_pay  = 7;
    // $sss_reg_ee = 12;
    // $sss_mpf_ee = 4;
    // $phic_ee = 9;
    // $hdmf_ee = 6;
    // $salary_deduction_taxable = 3;

    $total_taxable = ($basic_pay - $absences - $lates - $under_time + $salary_adjustment + $overtime_pay - $sss_reg_ee - $sss_mpf_ee - $phic_ee - $hdmf_ee - $salary_deduction_taxable);
    $witholding_tax = 0;

    if ($user->tax_application === "Non-Minimum") {
        if ($total_taxable <= 10417) {
            $witholding_tax = 0;
        } elseif ($total_taxable > 10417 && $total_taxable <= 16666.67) {
            $witholding_tax = ($total_taxable - 10417) * 0.15;
        } elseif ($total_taxable > 16666.67 && $total_taxable <= 33333.33) {
            $witholding_tax = ($total_taxable - 16667) * 0.2 + 937.5;
        } elseif ($total_taxable > 33333.33 && $total_taxable <= 83333.33) {
            $witholding_tax = ($total_taxable - 33333.33) * 0.25 + 4270.7;
        } elseif ($total_taxable > 83333.33 && $total_taxable <= 333333.33) {
            $witholding_tax = ($total_taxable - 83333.33) * 0.3 + 16770.7;
        } elseif ($total_taxable > 333333.33) {
            $witholding_tax = ($total_taxable - 333333.33) * 0.35 + 91770.7;
        }
    }

    return $witholding_tax;
}

function getUserSalaryAdjustmentAmount($user_id,$payroll_period_id,$payroll_cutoff){
    return PayrollSalaryAdjustment::where('payroll_period_id',$payroll_period_id)
        ->where('user_id',$user_id)
        ->where('status','Active')
        ->whereIn('payroll_cutoff',[$payroll_cutoff,'Every Cut-Off'])
        ->sum('amount');
}

function getUserGrossPayAmount($basic_pay,$absences_amount,$lates_amount,$undertime_amount,$salary_adjustment,$ot_amount,
    $meal_allowances,$salary_allowances,$out_allowances,$incentives_allowances,$discretionary_allowances,$transpo_allowances,$load_allowances){

    return $basic_pay - $absences_amount - $lates_amount - $undertime_amount + $salary_adjustment + $ot_amount + $meal_allowances + $salary_allowances + $out_allowances + $incentives_allowances + $reallocation_allowances + $discretionary_allowances + $transpo_allowances + $load_allowances;
}

function getUserTotalTaxableAmount($basic_pay,$absences_amount,$lates_amount,$undertime_amount,$salary_adjustment,$ot_amount,
    $sss_reg_ee,$sss_mpf_ee,$phic_ee,$hdmf_ee,$salary_deduction_taxable){

    return $basic_pay - $absences_amount - $lates_amount - $undertime_amount + $salary_adjustment + $ot_amount - $sss_reg_ee - $sss_mpf_ee - $phic_ee - $hdmf_ee - $salary_deduction_taxable;
}








