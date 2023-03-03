<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeWfh extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
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
