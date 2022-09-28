<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IclockTerminal extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'iclock_terminal';
}
