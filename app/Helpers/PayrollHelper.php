<?php
use App\Employee;
use App\PayrollSalaryAdjustment;
use App\PayrollOvertimeAdjustment;
use App\PayrollAttendance;
use App\PayrollEmployeeContribution;

function getUserWitholdingTaxAmount($user_id,$basic_pay,$absences_amount,$lates_amount,$undertime_amount,$salary_adjustment,$ot_amount,
    $sss_reg_ee,$sss_mpf_ee,$phic_ee,$hdmf_ee,$salary_deduction_taxable){
    $user = Employee::where('user_id',$user_id)
        ->first();
    

    $total_taxable = ($basic_pay - $absences_amount - $lates_amount - $undertime_amount + $salary_adjustment + $ot_amount - $sss_reg_ee - $sss_mpf_ee - $phic_ee - $hdmf_ee - $salary_deduction_taxable);
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

function getUserSalaryAdjustmentAmount($user_id,$payroll_period_id){
    return PayrollSalaryAdjustment::where('payroll_period_id',$payroll_period_id)
        ->where('user_id',$user_id)
        ->where('status','Active')
        // ->whereIn('payroll_cutoff',[$payroll_cutoff,'Every Cut-Off'])
        ->sum('amount');
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
    return PayrollOvertimeAdjustment::where('payroll_period_id',$payroll_period_id)
        ->where('user_id',$user_id)
        ->where('status','Active')
        // ->whereIn('payroll_cutoff',[$payroll_cutoff,'Every Cut-Off'])
        ->sum('amount');
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








