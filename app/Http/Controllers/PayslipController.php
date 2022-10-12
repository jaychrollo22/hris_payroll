<?php

namespace App\Http\Controllers;
use Excel;
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
        return view('payroll.pay_reg',
        array(
            'header' => 'Payroll',
            
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
