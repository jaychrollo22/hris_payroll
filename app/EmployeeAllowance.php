<?php

namespace App;

use App\Employee as AppEmployee;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeAllowance extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    //
    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }
    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }
}
