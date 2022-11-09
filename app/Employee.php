<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function payment_info()
    {
        return $this->hasOne(PaymentInformation::class);
    }
    public function ScheduleData()
    {
        return $this->hasMany(ScheduleData::class,'schedule_id','schedule_id');
    }
    public function immediate_sup_data()
    {
        return $this->belongsTo(User::class,'immediate_sup','id');
    }
    public function user_info()
    {
        return $this->belongsTo(User::class);
    }
  
}
