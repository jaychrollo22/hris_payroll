<?php

namespace App\Http\Controllers;
use App\Setting;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
{
    //

    public function view()
    {
        $settings = Setting::first();

        return view('settings.view',
        array(
            'header' => 'Handbooks',
            'settings' => $settings,
            
        ));
    }
    
    public function uploadIcon(Request $request)
    {
        $setting = Setting::first();
        if($setting == null)
        {
            $setting = new Setting;
        }
        $attachment = $request->file('file');
        $original_name = $attachment->getClientOriginalName();
        $name = time().'_'.$attachment->getClientOriginalName();
        $attachment->move(public_path().'/images/', $name);
        $file_name = '/images/'.$name;
        $setting->icon = $file_name;
        $setting->save();
        Alert::success('Successfully icon uploaded.')->persistent('Dismiss');
        return back();
    }
    public function uploadLogo(Request $request)
    {
        $setting = Setting::first();
        if($setting == null)
        {
            $setting = new Setting;
        }
        $attachment = $request->file('file');
        $original_name = $attachment->getClientOriginalName();
        $name = time().'_'.$attachment->getClientOriginalName();
        $attachment->move(public_path().'/images/', $name);
        $file_name = '/images/'.$name;
        $setting->logo = $file_name;
        $setting->save();
        Alert::success('Successfully icon uploaded.')->persistent('Dismiss');
        return back();
    }
}
