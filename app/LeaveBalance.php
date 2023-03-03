<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LeaveBalance extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    public function leave(){

        return $this->belongsTo(Leave::class);

    }

}
