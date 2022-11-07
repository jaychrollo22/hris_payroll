<?php

namespace App\Http\Controllers;
use App\Allowance;
use Illuminate\Http\Request;

class AllowanceController extends Controller
{
    //
    public function viewAllowances()
    {
        $allowance = Allowance::get();

        
    }
}
