<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeCompany extends Model
{
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
