<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approver extends Model
{
    public function userinfo(){

        return $this->belongsTo(User::class);

    }
    public function approverinfo(){

        return $this->belongsTo(User::class);

    }
}
