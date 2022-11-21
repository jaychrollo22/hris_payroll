<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    //
    public function loan_type()
    {
        return $this->belongsTo(LoanType::class);
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
