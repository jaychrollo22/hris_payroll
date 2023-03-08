<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Company extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    //
    public function employee_company()
    {
        return $this->hasMany(EmployeeCompany::class);
    }

    public function employee_has_company()
    {
        return $this->belongsTo(Employee::class,'id','company_id');
    }
}
