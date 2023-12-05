<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeePerformanceEvaluation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

}
