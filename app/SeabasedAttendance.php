<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SeabasedAttendance extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;


    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_code','employee_number');
    }
}
