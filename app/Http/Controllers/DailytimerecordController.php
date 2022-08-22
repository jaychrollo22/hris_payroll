<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DailytimerecordController extends Controller
{
    //

    public function dtr ()
    {
        return view('forms.dtr.dtr',
        array(
            'header' => 'forms',
        )); 
    }
}
