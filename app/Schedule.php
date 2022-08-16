<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    //

    public function ScheduleData()
    {
        return $this->hasMany(ScheduleData::class);
    }
}
