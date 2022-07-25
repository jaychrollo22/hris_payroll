<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkfromhomeController extends Controller
{
    //

    public function workfromhome()
    {
        return view('forms.wfh.wfh',
        array(
            'header' => 'forms',
        ));
    }
}
