<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeePerformanceEvaluation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function approver()
    {
        return $this->hasMany(EmployeeApprover::class,'user_id','user_id');
    } 

}
