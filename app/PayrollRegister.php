<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollRegister extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use SoftDeletes;

    protected $guareded = [];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }
}
