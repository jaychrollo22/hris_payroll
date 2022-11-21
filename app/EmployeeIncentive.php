<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeIncentive extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function incentive()
    {
        return $this->belongsTo(Incentive::class);
    }
}
