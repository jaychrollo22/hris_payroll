<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PayrollEmployeeContribution extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','payroll_period_id','company','sss_reg_ee','sss_mpf_ee','phic_ee','hdmf_ee',
        'sss_reg_er','sss_mpf_er','sss_ec','phic_er','hdmf_er','payment_schedule'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }

    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class,'payroll_period_id','id');
    }
}
