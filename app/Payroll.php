<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    //
    protected $connection = 'sqlsrv_payroll';
    protected $table = 'payroll';


    public function payroll()
    {
        return $this->hasMany(Payroll::class,'emp_code','emp_code');
    }
}
