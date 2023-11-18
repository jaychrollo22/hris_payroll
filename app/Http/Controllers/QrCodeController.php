<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\Employee;
use QRCode;

use Illuminate\Support\Facades\Crypt;

class QrCodeController extends Controller
{
    public function generateQrCode($employee_id)
    {

        $employee_encrpyt_id = Crypt::encrypt($employee_id);

        $employee = Employee::select('first_name','last_name')->where('id',$employee_id)->first();
        if($employee){
            $qr_text = 'https://hris.pivi.com.ph/qr/'.$employee_encrpyt_id;
            $file_name = $employee_id . '_' . $employee->first_name . '_' . $employee->last_name;
            $filepath = base_path().'/public/qr_codes/'.$file_name.'.png';
            QRCode::text($qr_text)->setSize(100)->setMargin(3)->setOutfile($filepath)->png();
            return redirect('/qr_codes/'.$file_name.'.png');
        }
    }

    public function viewQrCode($employee_id)
    {
        // $employee_id = Crypt::decrypt($employee_id);
        $employee = Employee::select('first_name','last_name','personal_number','position','company_id','status')
                                    ->with('company')
                                    ->where('id',$employee_id)
                                    ->first();
        if($employee){
            return view('qr.index',array('employee'=>$employee));
        }
    }

    public function viewDecyptQrCode($employee_id)
    {
        $employee_id = Crypt::decrypt($employee_id);
        $employee = Employee::select('first_name','last_name','personal_number','position','company_id','status')
                                    ->with('company')
                                    ->where('id',$employee_id)
                                    ->first();
        if($employee){
            return view('qr.index',array('employee'=>$employee));
        }
    }




}
