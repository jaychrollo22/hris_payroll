<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollSalaryAdjustment extends Model
{
    use SoftDeletes;

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id')->select('id','user_id','employee_number','first_name','last_name','middle_name','company_id','department_id','schedule_id');
    }
}
