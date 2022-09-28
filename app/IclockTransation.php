<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IclockTransation extends Model
{
    //
    protected $connection = 'sqlsrv';
    protected $table = 'iclock_transaction';

    public function emp_data()
    {
        return $this->belongsTo(PersonnelEmployee::class,'emp_code','emp_code');
    }
    public function location()
    {
        return $this->belongsTo(IclockTerminal::class,'terminal_id','id');
    }

}
