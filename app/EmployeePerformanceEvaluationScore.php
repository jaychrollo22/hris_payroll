<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeePerformanceEvaluationScore extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }

    public function ppr()
    {
        return $this->belongsTo(EmployeePerformanceEvaluation::class,'employee_performance_evaluation_id','id')->select('id','user_id','calendar_year','period');
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
