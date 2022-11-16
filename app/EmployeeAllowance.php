<?php

namespace App;

use App\Employee as AppEmployee;
use Illuminate\Database\Eloquent\Model;

class EmployeeAllowance extends Model
{
    //
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }
}
