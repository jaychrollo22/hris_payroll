<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    public function employee_company()
    {
        return $this->hasMany(EmployeeCompany::class);
    }
}
