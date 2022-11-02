<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HrEmployee extends Model
{
    //

    
    protected $connection = 'sqlsrv_local';
    protected $table = 'hr_employee';
}
