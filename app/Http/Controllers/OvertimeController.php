<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    //

    public function overtime ()
    { 
        
        // $leave_types = Leave::get();
        return view('forms.overtime.overtime',
        array(
            'header' => 'forms',
        ));

    }
}
