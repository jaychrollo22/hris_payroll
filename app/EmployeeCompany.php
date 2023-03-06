<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeCompany extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    //
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_code', 'employee_code');
    }
    public function company()
    {
        return $this->hasMany(Company::class);
    }
}
