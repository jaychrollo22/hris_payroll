<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeIncentive extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function incentive()
    {
        return $this->belongsTo(Incentive::class);
    }
}
