<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonnelEmployee extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'personnel_employee';
}
