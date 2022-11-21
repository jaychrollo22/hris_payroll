<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    public function leave(){

        return $this->belongsTo(Leave::class);

    }

}
