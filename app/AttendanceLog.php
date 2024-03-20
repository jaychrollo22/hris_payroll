<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    //
    public function employee()
    {
        return $this->belongsTo(Employee::class,'emp_code','employee_number');
    }
}
