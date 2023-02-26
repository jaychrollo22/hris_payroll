<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeWfh extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function schedule()
    {
        return $this->hasMany(ScheduleData::class,'schedule_id','schedule_id');
    }  

    public function approver()
    {
        return $this->hasMany(EmployeeApprover::class,'user_id','user_id');
    }  
}
