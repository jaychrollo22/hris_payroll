<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeOvertime extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function pending()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
