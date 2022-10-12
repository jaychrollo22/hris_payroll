<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    //
    protected $connection = 'sqlsrv_payroll';
    protected $table = 'payroll';
}
