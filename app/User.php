<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'id','user_id');
    }
    public function approvers()
    {
        return $this->hasMany(EmployeeApprover::class,'user_id','id');
    }
    public function subbordinates()
    {
        return $this->hasMany(Employee::class,'immediate_sup','id');
    }

    public function emp_leave()
    {
        return $this->hasMany(EmployeeLeave::class);
    }    

    public function emp_ot()
    {
        return $this->hasMany(EmployeeOvertime::class);
    }   
    
    public function emp_wfh()
    {
        return $this->hasMany(EmployeeWfh::class);
    }       
    
    public function emp_ob()
    {
        return $this->hasMany(EmployeeOb::class);
    }     
    
    public function emp_dtr()
    {
        return $this->hasMany(EmployeeDtr::class);
    }         
}
