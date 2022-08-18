<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeApprover extends Model
{
    //
    public function approver_data()
    {
        return $this->belongsTo(User::class,'approver_id','id');
    }
}
