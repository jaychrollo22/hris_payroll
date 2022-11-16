<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PayrollController extends Controller
{
    //
    public function totalExpense_report()
    {
        return view('reports.totalExpense_report', array(
            'header' => 'reports',
        ));
    }
    public function payroll_report()
    {
        return view('reports.payroll_report', array(
            'header' => 'reports',
        ));
    }
}
