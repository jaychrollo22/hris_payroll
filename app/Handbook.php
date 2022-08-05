<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Handbook extends Model
{
    //
    public function userinfo()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
