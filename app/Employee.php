<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Employee extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    //

    public function contact_person(){
        return $this->hasOne(EmployeeContactPerson::class,'user_id','user_id');
    }

    public function classification_info()
    {
        return $this->belongsTo(Classification::class,'classification','id');
    }
    public function level_info()
    {
        return $this->belongsTo(Level::class,'level','id');
    }
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

    public function attendances() {
        return $this->hasMany(Attendance::class,'employee_code','employee_number');
    }

    public function leaves() {
        return $this->hasMany(EmployeeLeave::class,'user_id','user_id');
    }

    public function obs() {
        return $this->hasMany(EmployeeOb::class,'user_id','user_id');
    }

    public function wfhs() {
        return $this->hasMany(EmployeeWfh::class,'user_id','user_id');
    }

    public function employee_leave_credits() {
        return $this->hasMany(EmployeeLeaveCredit::class,'user_id','user_id')->orderBy('leave_type','ASC');
    }
}
