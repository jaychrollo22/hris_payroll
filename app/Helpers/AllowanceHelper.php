<?php
use App\EmployeeAllowance;

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
        });
}










