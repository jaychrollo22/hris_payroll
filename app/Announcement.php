<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    //

    public function user()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
