<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WfhTask extends Model
{
    public function task()
    {
        return $this->belongsTo(EmployeeWfh::class,'wfh_id','id');
    }
}
