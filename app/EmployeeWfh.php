<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeWfh extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function schedule()
    {
        return $this->hasMany(ScheduleData::class,'schedule_id','schedule_id');
    }  
}
