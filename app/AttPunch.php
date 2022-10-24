<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttPunch extends Model
{
    //

    protected $connection = 'sqlsrv_local';

    public function personal_data()
    {
        return $this->hasOne(HrEmployee::class,'id','employee_id');
    }
}
