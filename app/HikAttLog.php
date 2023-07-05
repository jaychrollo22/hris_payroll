<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HikAttLog extends Model
{
    protected $connection = 'sqlsrv_hik';
    protected $table = 'attlog';

    public function employee(){
        return $this->hasOne(Employee::class,'employeeID','employee_number');
    }
}
