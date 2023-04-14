<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeEarnedLeave extends Model
{
    use SoftDeletes;
   
    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }
    public function leave_type_info()
    {
        return $this->belongsTo(Leave::class,'leave_type','id');
    }
}
