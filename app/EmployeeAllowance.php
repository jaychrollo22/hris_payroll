<?php

namespace App;

use App\Employee as AppEmployee;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;


class EmployeeAllowance extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id')->select('id','user_id','first_name','last_name','middle_name','company_id','department_id');
    }
    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }
}
