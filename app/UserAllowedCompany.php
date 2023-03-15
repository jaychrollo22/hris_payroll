<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAllowedCompany extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
}

