<?php
use App\EmployeeAllowance;

function getUserAllowanceAmount($user_id,$allowance_id,$cut_off,$no_of_days_worked){
    $amount = 0;

    $allowances = EmployeeAllowance::select('id','allowance_amount')
        ->where('user_id',$user_id)
        ->where('allowance_id',$allowance_id)
        ->where('status','Active')
        ->whereIn('schedule',[$cut_off,'Every Cut-Off'])
        ->get();
    
    foreach($allowances as $allowance){
        $amount += $allowance->application == 'Daily' ? ($allowance->allowance_amount * $no_of_days_worked) : $allowance->allowance_amount;
    }

    return $amount;
}

function getUserAllowances($user_id,$allowance_id = null,$date = null){
    return EmployeeAllowance::select('id','allowance_id','allowance_amount')
        ->with(['allowance' => function ($query) {
            $query->select('id', 'name');
        }])
        ->where('user_id',$user_id)
        ->when($allowance_id,function($q) use($allowance_id){
            $q->where('allowance_id',$allowance_id);
        })
        ->where('status','Active')
        ->get()
        ->map(function ($employee_allowance) {
            return [
                'id' => $employee_allowance->id,
                'allowance_id' => $employee_allowance->allowance_id,
                'allowance_amount' => $employee_allowance->allowance_amount,
                'allowance_type' => $employee_allowance->allowance->name
            ];
        })
        ->toArray();
}










