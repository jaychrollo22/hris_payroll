<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HikAttLog extends Model
{
    protected $connection = 'mysql_hik';
    protected $table = 'attlog';
}
