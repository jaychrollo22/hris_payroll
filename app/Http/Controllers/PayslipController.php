<?php

namespace App\Http\Controllers;
use Excel;
use App\Payroll;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PayslipController extends Controller
{
    //
    public function view ()
    {
      
        return view('payslips.payslips',
        array(
            'header' => 'payslips',
            
        ));
    }
    public function payroll_datas()
    {
        $payrolls = Payroll::select('date_from','date_to','auditdate')->orderBy('date_from','desc')->get()->unique('date_from');
        $payroll_employees = Payroll::get();
        
        // dd($payrolls);
        return view('payroll.pay_reg',
        array(
            'header' => 'Payroll',
            'payrolls' => $payrolls,
            'payroll_employees' => $payroll_employees,
            
        ));
    }
    function import(Request $request)
    {
     $path = $request->file('file')->getRealPath();

     $data = Excel::load($path)->get();
     dd($data);
     Alert::success('Successfully Import')->persistent('Dismiss');
     return back();
    }
}
