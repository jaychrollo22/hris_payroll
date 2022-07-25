<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IclockTransation extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'iclock_transaction';

}
