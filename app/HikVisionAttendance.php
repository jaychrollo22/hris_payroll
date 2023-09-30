<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HikVisionAttendance extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_code','employee_number');
    }
}
