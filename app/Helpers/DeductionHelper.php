<?php
use App\EmployeeDeduction;

function getUserDeductionAmount($user_id,$deduction_id,$cut_off){
    return EmployeeDeduction::select('id','amortization')
        ->where('user_id',$user_id)
        ->where('deduction_id',$deduction_id)
        ->where('status','Active')
        ->whereIn('type_of_deduction',[$cut_off,'Every Cut-Off'])
        ->sum('amortization');
}