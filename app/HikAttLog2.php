<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HikAttLog2 extends Model
{
    protected $connection = 'sqlsrv_hik';
    protected $table = 'attlog';
}
