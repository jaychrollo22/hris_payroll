<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ScheduleData extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function schedule_info()
    {
        return $this->belongsTo(Schedule::class,'schedule_id','id');
    }
}
