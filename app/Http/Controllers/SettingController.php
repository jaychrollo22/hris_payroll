<?php

namespace App\Http\Controllers;
use App\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    //

    public function view()
    {
        $settings = Setting::get();

        return view('settings.view',
        array(
            'header' => 'Handbooks',
            'settings' => $settings,
            
        ));
    }
}
