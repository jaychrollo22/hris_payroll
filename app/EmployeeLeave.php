<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function leave()
    {
        return $this->belongsTo(Leave::class,'leave_type','id');
    } 
    
    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }     

    public function schedule()
    {
        return $this->hasMany(ScheduleData::class,'schedule_id','schedule_id');
    }   
    
    public function approver()
    {
        return $this->belongsTo(EmployeeApprover::class,'user_id','user_id');
    }       
}
