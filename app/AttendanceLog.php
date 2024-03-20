<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    //
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_number','emp_code');
    }
}
