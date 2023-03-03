<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeApprover extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public function approver_info()
    {
        return $this->belongsTo(User::class,'approver_id','id');
    }
    public function approver_data()
    {
        return $this->belongsTo(User::class,'approver_id','id');
    }

    public function user_info(){

        return $this->belongsTo(User::class,'user_id','id');

    }    
    public function leave_info(){

        return $this->belongsTo(EmployeeLeave::class,'user_id','user_id');

    }       
}
