<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeApprover extends Model
{
    //
    public function approver_info()
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
