<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeLoan extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
 
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    } 

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }
}
