<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use QRCode;

class QrCodeController extends Controller
{
    public function generateQrCode(Request $request)
    {
        $qr_text = $request->q;
        $filename = base_path().'/public/qr_codes/'.$qr_text.'.png';
        QRCode::text($qr_text)->setSize(40)->setMargin(2)->setOutfile($filename)->png();
        return url('/qr_codes/'.$qr_text.'.png');
    }


}
