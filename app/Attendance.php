<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_code','employee_number')->select('id','user_id','employee_number','first_name','last_name','middle_name','company_id','department_id');
    }
}
