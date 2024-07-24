<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeCustomizedPprApprover extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function first_approver_info()
    {
        return $this->belongsTo(User::class,'first_approver_id','id');
    }

    public function second_approver_info()
    {
        return $this->belongsTo(User::class,'second_approver_id','id');
    }

}
