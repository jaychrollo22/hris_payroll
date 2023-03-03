<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Handbook extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    public function userinfo()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
