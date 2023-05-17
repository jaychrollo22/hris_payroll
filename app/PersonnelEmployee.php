<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonnelEmployee extends Model
{
    //
    protected $connection = 'mysql_srv';
    protected $table = 'personnel_employee';

    // public function attendances()
    // {
    //     return $this->hasMany(Attendance::class,'emp_code','employee_code');
    // }

    public function employee() {
        return $this->setConnection('mysql')->belongsTo(Employee::class,'emp_code','employee_number')->select('id','user_id','employee_number','first_name','last_name');
    }
    public function attendances() {
        return $this->setConnection('mysql')->hasMany(Attendance::class,'employee_code','emp_code');
    }
    
}
