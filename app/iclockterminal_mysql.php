<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class iclockterminal_mysql extends Model
{
    protected $connection = 'mysql_srv';
    protected $table = 'iclock_terminal';
}
