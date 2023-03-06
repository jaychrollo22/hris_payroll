<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Approver extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    public function userinfo(){

        return $this->belongsTo(User::class);

    }
    public function approverinfo(){

        return $this->belongsTo(User::class);

    }
}
