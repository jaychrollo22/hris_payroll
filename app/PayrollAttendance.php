<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PayrollAttendance extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }
}