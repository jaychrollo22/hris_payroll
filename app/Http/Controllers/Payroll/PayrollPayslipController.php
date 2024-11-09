<?php

namespace App\Http\Controllers\Payroll;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\PayrollRegister;
use App\PayrollPeriod;
use PDF;

class PayrollPayslipController extends Controller
{

    public function generatePayslip(PayrollRegister $payrollRegister)
    {
        $pdf = PDF::loadView('payroll_payslip.print_payslip', compact('payrollRegister'));
        return $pdf->stream('payroll_payslip.print_payslip');
        // return $pdf->download('payroll_payslip.print_payslip');
    }

    
}
