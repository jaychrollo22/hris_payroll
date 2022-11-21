<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonnelEmployee extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'personnel_employee';

    // public function attendances()
    // {
    //     return $this->hasMany(Attendance::class,'emp_code','employee_code');
    // }

    public function attendances() {
        return $this->setConnection('mysql')->hasMany(Attendance::class,'employee_code','emp_code');
    }
    
}
