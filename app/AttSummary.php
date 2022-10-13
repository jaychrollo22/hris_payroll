<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttSummary extends Model
{
    //
    protected $connection = 'sqlsrv_payroll';
    protected $table = 'att_summary';
}
